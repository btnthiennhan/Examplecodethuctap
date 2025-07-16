// src/order/entities/order.entity.ts
import { Entity, PrimaryGeneratedColumn, Column, OneToMany } from 'typeorm';
import { OrderDetail } from './order-detail.entity';

@Entity('orders')
export class Order {
  @PrimaryGeneratedColumn()
  id: number;

  @Column({ unique: true })
  order_id: string;

  @Column()
  user_id: number;

  @Column()
  total_price: number;

  @Column()
  shipping_address: string;

  @Column({ nullable: true })
  notes: string;

  @Column()
  payment_method: string;

  @Column()
  status: string;

  @OneToMany(() => OrderDetail, (orderDetail) => orderDetail.order)
  orderDetails: OrderDetail[];
}