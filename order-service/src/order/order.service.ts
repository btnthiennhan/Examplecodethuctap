import { Injectable } from '@nestjs/common';
import { CreateOrderDto } from './dto/create-order.dto';
import { InjectRepository } from '@nestjs/typeorm';
import { Repository } from 'typeorm';
import { Order } from './entities/order.entity';
import { OrderDetail } from './entities/order-detail.entity';
import { Logger } from '@nestjs/common';

@Injectable()
export class OrderService {
  private readonly logger = new Logger(OrderService.name);

  constructor(
    @InjectRepository(Order)
    private readonly orderRepository: Repository<Order>,
    @InjectRepository(OrderDetail)
    private readonly orderDetailRepository: Repository<OrderDetail>,
  ) {}

  async createOrder(createOrderDto: CreateOrderDto) {
    this.logger.log(`Bắt đầu lưu đơn hàng: ${JSON.stringify(createOrderDto)}`);
    try {
      const { order_id, user_id, total_price, shipping_address, notes, payment_method, status, items } = createOrderDto;

      // Kiểm tra đơn hàng trùng lặp
      const existingOrder = await this.orderRepository.findOne({ where: { order_id } });
      if (existingOrder) {
        this.logger.warn(`Đơn hàng ${order_id} đã tồn tại`);
        return { success: true, order_id };
      }

      // Lưu thông tin đơn hàng chính
      const order = this.orderRepository.create({
        order_id,
        user_id,
        total_price,
        shipping_address,
        notes,
        payment_method,
        status,
      });
      await this.orderRepository.save(order);
      this.logger.log(`Lưu đơn hàng chính thành công: ${order_id}`);

      // Lưu chi tiết đơn hàng
      for (const item of items) {
        const orderDetail = this.orderDetailRepository.create({
          order_id: order.order_id, // Chuỗi mã đơn hàng
          orderId: order.id, // Khóa ngoại liên kết với orders.id
          product_id: item.product_id,
          quantity: item.quantity,
          price: item.price,
        });
        await this.orderDetailRepository.save(orderDetail);
        this.logger.log(`Lưu chi tiết đơn hàng thành công: product_id=${item.product_id}`);
      }

      return { success: true, order_id: order.order_id };
    } catch (error) {
      this.logger.error(`Lỗi khi lưu đơn hàng: ${error.message}`, error.stack);
      throw error;
    }
  }
}