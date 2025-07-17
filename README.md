Microservice Demo with Redis Queue



Mục tiêu



Dự án mô phỏng một hệ thống đơn giản sử dụng kiến trúc \*\*microservice\*\* với Redis làm hàng đợi trung gian, kết nối giữa:



\- `testyii` – Giao diện người dùng (Yii 2.0 - PHP)

\- `queue-service` – Middleware (Node.js, đẩy vào Redis)

\- `order-service` – Backend xử lý đơn hàng (NestJS, lấy từ Redis và lưu vào MySQL)



---



\## Yêu cầu cài đặt



Trước khi bắt đầu, đảm bảo bạn đã cài đặt các công cụ sau:



| Công cụ      | Vai trò                        |

|--------------|---------------------------------|

| XAMPP      | Chạy Apache \& MySQL cho Yii2   |

| PHP 8.2    |đảm bảo PHP 8.2 trở lên  |

| VS Code    | Soạn thảo mã nguồn              |

| Redis      | Hàng đợi trung gian             |

| Composer   | Cài đặt package cho Yii2        |

| Node.js    | Chạy `queue-service` \& `order-service` |



🔗 Redis cho Windows có thể tải tại:  

(https://github.com/tporadowski/redis/releases)



---



Cấu trúc dự án



```



Examplecodethuctap/

├── testyii/            # PHP (Yii2) frontend

├── queue-service/      # Node.js middleware (Redis Producer)

├── order-service/      # NestJS backend (Redis Consumer)

├── my_store.sql/      # CSDL MySQL



````



---



Cài đặt \& Khởi chạy
Nhập file my_store.sql vào MySQL.



Clone và cài đặt các gói



```bash

\# Tải project về

git clone <https://github.com/btnthiennhan/Examplecodethuctap.git>

cd Examplecodethuctap



\# Cài đặt PHP packages cho testyii

cd testyii

composer install



\# Cài đặt Node packages cho queue-service

cd ../queue-service

npm install



\# Cài đặt Node packages cho order-service

cd ../order-service

npm install

````



---



\### 2. Chạy các dịch vụ



> Nhớ bật \*\*Redis\*\* và \*\*XAMPP\*\* (Apache + MySQL) trước khi chạy.



```bash

\# Mở Redis server (tùy hệ điều hành)



\# Chạy queue-service (đẩy dữ liệu vào Redis)

cd queue-service

node queue.js



\# Chạy order-service (nhận từ Redis và lưu vào MySQL)

cd ../order-service

npm run start

```



---



\### 3. Truy cập frontend Yii2



Mở trình duyệt và truy cập:



```

http://localhost/testyii/web

```



Tại đây bạn có thể:



\* Đăng ký tài khoản

\* Chọn sản phẩm

\* Truy cập giỏ hàng

\* Nhập thông tin và chọn "Thanh toán trực tiếp"



---



\## ⚙️ Cách hoạt động



1\. Người dùng thao tác trên web `testyii`

2\. Khi thanh toán, thông tin đơn hàng được gửi đến `queue-service`

3\. `queue-service` đẩy dữ liệu vào hàng đợi Redis

4\. `order-service` đang lắng nghe Redis → nhận dữ liệu và lưu vào MySQL



---



\## Kiểm thử hàng đợi



Hệ thống đảm bảo \*\*tính ổn định và không mất dữ liệu\*\*, kể cả khi service chính bị tắt:



\* Tắt `order-service` trong lúc ấn nút thanh toán

\* Dữ liệu vẫn được đưa vào Redis queue

\* Khi mở lại `order-service`, đơn hàng sẽ được xử lý và lưu ngay vào MySQL



⟶ Đây là cơ chế hàng đợi của microservices.



---



\## Ghi chú



\* Dữ liệu ghi log từ `order-service` sẽ hiển thị chi tiết đơn hàng xử lý

\* Bạn có thể mở `Redis CLI` để xem hàng đợi thủ công

\* Mỗi phần có thể triển khai độc lập (theo đúng triết lý microservice)



---



\## Tác giả \& Liên hệ



Dự án thử nghiệm nhằm thực hành microservice với PHP, NodeJS và Redis. Không đại diện cho code cuối cùng.




