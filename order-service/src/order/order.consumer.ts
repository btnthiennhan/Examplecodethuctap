import { Process, Processor } from '@nestjs/bull';
import { Job } from 'bull';
import { OrderService } from './order.service';
import { Injectable, Logger } from '@nestjs/common';
import { CreateOrderDto } from './dto/create-order.dto';

@Processor('order-queue')
@Injectable()
export class OrderConsumer {
  private readonly logger = new Logger(OrderConsumer.name);

  constructor(private readonly orderService: OrderService) {
    this.logger.log('OrderConsumer initialized');
  }

  @Process()
  async handleOrder(job: Job<any>) {
   
    this.logger.log(`🔔 Nhận được job mới trong order-queue: ${JSON.stringify(job.data)}`);

    try {
      let orderData: CreateOrderDto;
      if (job.data.body) {
        const cleanedBody = job.data.body.replace(/\\/g, '');
        orderData = JSON.parse(cleanedBody);
      } else {
        orderData = job.data;
      }

      this.logger.log(`🔔 Dữ liệu đơn hàng sau khi parse: ${JSON.stringify(orderData)}`);
      const result = await this.orderService.createOrder(orderData);
      this.logger.log(`✅ Đơn hàng đã lưu với ID: ${result.order_id}`);
      return result;
    } catch (error) {
      this.logger.error(`❌ Lỗi khi xử lý đơn hàng: ${error.message}`, error.stack);
      throw error;
    }
  }
}