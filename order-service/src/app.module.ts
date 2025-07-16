import { Module, Logger, OnModuleInit } from '@nestjs/common';
import { TypeOrmModule, InjectDataSource } from '@nestjs/typeorm';
import { BullModule } from '@nestjs/bull'; // Thêm BullModule
import { OrderModule } from './order/order.module';
import { DataSource } from 'typeorm';

@Module({
  imports: [
    TypeOrmModule.forRoot({
      type: 'mysql',
      host: '127.0.0.1',
      port: 3306,
      username: 'root',
      password: '',
      database: 'my_store',
      entities: [__dirname + '/**/*.entity{.ts,.js}'],
      synchronize: true,
    }),
    BullModule.forRoot({
      redis: {
        host: 'localhost',
        port: 6379,
      },
    }),
    // Đăng ký queue order-queue
    BullModule.registerQueue({
      name: 'order-queue',
    }),
    OrderModule,
  ],
})
export class AppModule implements OnModuleInit {
  private readonly logger = new Logger(AppModule.name);

  constructor(@InjectDataSource() private readonly dataSource: DataSource) {}

  async onModuleInit() {
    
    try {
      await this.dataSource.query('SELECT 1');
      this.logger.log('✅ Database connected successfully');
    } catch (error) {
      this.logger.error('❌ Failed to connect to database', error.stack);
      throw error;
    }

    
    try {
      const Redis = require('ioredis');
      const redis = new Redis({ host: 'localhost', port: 6379 });
      await redis.ping();
      this.logger.log('✅ Redis connected successfully');
      redis.disconnect();
    } catch (error) {
      this.logger.error('❌ Failed to connect to Redis', error.stack);
      throw error;
    }
  }
}