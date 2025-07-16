import { Injectable } from '@nestjs/common';
import { InjectQueue } from '@nestjs/bull';
import { Queue } from 'bull';

@Injectable()
export class OrderProducerService {
  constructor(@InjectQueue('order-queue') private orderQueue: Queue) {}

  async addOrderJob(data: any) {
    await this.orderQueue.add(data, {
      removeOnComplete: true,
      attempts: 5,
      backoff: 5000, // Retry sau 5s nếu fail
    });
  }
}
