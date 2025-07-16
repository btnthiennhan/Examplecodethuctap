import { IsArray, IsEnum, IsNumber, IsOptional, IsString } from 'class-validator';
import { Transform, Type } from 'class-transformer'; // Thêm Type

// Class riêng cho item
export class OrderItemDto {
  @IsNumber()
  product_id: number;

  @IsNumber()
  @Transform(({ value }) => parseInt(value, 10)) 
  quantity: number;

  @IsNumber()
  @Transform(({ value }) => parseFloat(value)) 
  price: number;
}

export class CreateOrderDto {
  @IsString()
  order_id: string;

  @IsNumber()
  user_id: number;

  @IsNumber()
  total_price: number;

  @IsString()
  shipping_address: string;

  @IsString()
  @IsOptional()
  notes: string;

  @IsEnum(['cash', 'momo'])
  payment_method: 'cash' | 'momo';

  @IsEnum(['paid', 'pending'])
  status: 'paid';

  @IsOptional()
  @IsString()
  momo_transaction_id?: string;

  @IsArray()
  @Type(() => OrderItemDto)
  items: OrderItemDto[];
}