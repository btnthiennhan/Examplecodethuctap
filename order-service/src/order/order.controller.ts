// src/order/order.controller.ts
import { Controller, Post, Body } from '@nestjs/common';
import { OrderService } from './order.service';
import { CreateOrderDto } from './dto/create-order.dto';

@Controller('order')
export class OrderController {
  constructor(private readonly orderService: OrderService) {}

  // // API nhận đơn hàng từ PHP
  // @Post()
  // async createOrder(@Body() createOrderDto: CreateOrderDto) {
  //   return await this.orderService.createOrder(createOrderDto);
  // }
}