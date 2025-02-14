# PeakPulseMarket - Ecommerce Store 🛒  

## 📌 Tags  

[![License: MIT](https://img.shields.io/badge/License-MIT-yellow.svg)](https://github.com/subodhdhyani/PeakPulseMarket-EcommerceStore/blob/master/LICENSE)
[![Laravel](https://img.shields.io/badge/Laravel-v11.0.0-red.svg)](https://laravel.com/)
[![MySQL](https://img.shields.io/badge/MySQL-v8.0-blue.svg)](https://www.mysql.com/)
[![Razorpay](https://img.shields.io/badge/Razorpay-v2.9.0-green.svg)](https://razorpay.com/)
[![Chart.js](https://img.shields.io/badge/Chart.js-v4.4.7-blue.svg)](https://www.chartjs.org/)
[![Bootstrap](https://img.shields.io/badge/Bootstrap-v5.2.3-purple.svg)](https://getbootstrap.com/)
[![JavaScript](https://img.shields.io/badge/JavaScript-vECMAScript_2025-yellow.svg)](https://www.javascript.com/)
[![jQuery](https://img.shields.io/badge/jQuery-v3.7.1-blue.svg)](https://jquery.com/)
[![Toastr](https://img.shields.io/badge/Toastr-v2.1.4-orange.svg)](https://github.com/CodeSeven/toastr)
[![SweetAlert](https://img.shields.io/badge/SweetAlert-v2.1.2-yellowgreen.svg)](https://sweetalert.js.org/)
[![Barry DOMPDF](https://img.shields.io/badge/Barry--DOMPDF-v3.0.0-blue.svg)](https://github.com/barryvdh/laravel-dompdf)

---

## 🛍️ About PeakPulseMarket  


PeakPulseMarket is a **responsive web application** built on **Laravel**, providing a seamless **eCommerce experience**. Users can **browse products, place orders, and manage cancellations or returns**, while admins efficiently handle **order processing, shipping, and refunds**. The platform integrates the **Razorpay payment gateway**, supports **queue processing with Supervisor**, and provides **API integration for** third-party delivery services. 

---

## 🚀 Features  

### 👥 User Features  
- Browse Products and Place Orders  
- Secure Payments via **Razorpay**  
- Cancel/Return Orders  
- Receive Email & inApp Notifications
- Give Product Reviews

### 🔧 Admin Features  
- **Manage Orders**: Process **New, Shipped,Returned,and Refunded Orders**  
- **Dashboard Analytics**: View Order Statistics using **Chart.js**  
- **Payment Processing**: Handle refunds via Razorpay  
- **Notification**: Uses Laravel Notification to send in App Notification 
- **Automated Queue Processing**: Uses Laravel Queues & **Supervisor**
- **Dynamic UI Management**: Admins can add/edit Categories, Products, and Modify UI details dynamically

### 🔧 Other Features  
- **Third-Party API Integration**: Manage **Order Deliveries** via API
   
---

## 🔑 Authentication & User Roles

- **Single Login Form**: Both **Users and Admins** log in through the same form.  
- **Role-Based Redirection**:  
  - 🛠 **Admins** → Redirected to the **Admin Dashboard**  
  - 🛍 **Users** → Redirected to the **Browse Products** page  
- **Custom Laravel Guard**: Implements role-based Authentication using a **Custom Guard**
  
---

## 🛠 Technologies Used  

- **Frontend**: HTML, CSS, Bootstrap, Blade Template, JavaScript, jQuery 
- **Backend**: Laravel 
- **Database**: MySQL  
- **Payment Gateway**: Razorpay  
- **Data Visualization**: Chart.js  
- **Queue Processing**: Laravel Queues (Automatically Managed via **Supervisor**)  
- **Notifications**: Laravel Notifications (Email & System Alerts)  
- **Library** : Toastr, SweetAlert
- **Laravel Package** → Barryvdh DOMPDF

---

## 🔗 Requirements  

- PHP **>=8.0**  
- Laravel **v11.0**  
- Composer  
- MySQL **>=8.0**  
- Razorpay Account  
- Supervisor (for queue management)  

---

## ⚠️ Changes in Project  

- Set the Razorpay key and other Details inside `.env`:  
    ```env
    RAZORPAY_KEY_ID=your_key_id
    RAZORPAY_KEY_SECRET=your_key_secret
    *(Also inside env i used gmail for sending different email/notification)*
    MAIL_MAILER=smtp
    MAIL_HOST=smtp.gmail.com
    MAIL_PORT=587
    MAIL_USERNAME=emailaddress@gmail.com
    MAIL_PASSWORD="mmmm uiyt nnnn hnxf"    #App Password created by  https://myaccount.google.com/apppasswords
    MAIL_ENCRYPTION=tls
    MAIL_FROM_ADDRESS=emailaddress@gmail.com
    MAIL_FROM_NAME="PeakPulseMarket"
    ```
- Create a **MySQL database** named `peakpulsemarket`.  

---

## ⚙️ Installation  

1. **Clone the repository**  
    ```sh
    git clone https://github.com/Subodhdhyani/PeakPulseMarket-EcommerceStore.git
    cd PeakPulseMarket-EcommerceStore
    ```

2. **Install dependencies**  
    ```sh
    composer install
    ```

3. **Copy `.env.example` to `.env` and update environment variables**  
    ```sh
    cp .env.example .env
    php artisan key:generate
    ```

4. **Configure the database and migrate**  
    ```sh
    php artisan migrate --seed
    ```
    
5. **Barryvdh DomPDF Setup and Razorpay Payment Setup**
   ```sh
    composer require barryvdh/laravel-dompdf
    php artisan vendor:publish --provider="Barryvdh\DomPDF\ServiceProvider"
   
    composer require razorpay/razorpay:2.*
    ```
    
6. **Set Up Storage (for file uploads, images, etc.)**
   ```sh
   php artisan storage:link
   ```
   
7. **Set up queues (Supervisor required for auto-processing || In localhost Supervisisor not required)**  
    ```sh
    php artisan queue:work
    
    php artisan queue:work --queue=delivery  #for individual queue to run on localhost
    ```

8. **Serve the application**  
    ```sh
    php artisan serve
    ```

9. **Access the application in your browser**  
    - **User:** [http://localhost:8000](http://localhost:8000)  
    - **Admin Panel:** [http://localhost:8000/admin/dashboard](http://localhost:8000/admin/dashboard)  

---


## 🔑 Admin Login Credentials  

- **Email:** `admin@peakpulsemarket.com`  
- **Password:** `admin@12345`  

*(Modify credentials in the **seeder file** before running `php artisan db:seed` If needed to change admin details.)*  

---

## 🔗 API for Third-Party for Mark Order Delivered 

- This project includes **REST APIs** for order deliveries, allowing third-party to mark order delivered.  

---


## 📜 License  

This project is licensed under the **MIT License** – see the [LICENSE](LICENSE) file for details.  

---

## 👥 Contributors  

- [Subodh Dhyani](https://github.com/subodhdhyani)  

---

## ⚠️ Disclaimer  

This eCommerce platform is built for demonstration and development purposes. Any product names, logos, or brand references used in this repository are purely hypothetical and do not represent real businesses.  

---

## 📸 Screenshots  

### 👤 User Images

[Homepage](https://github.com/user-attachments/assets/9cbcd35b-733e-42bb-a9c6-ca7b3641e703)  
[Homepage Small Screen](https://github.com/user-attachments/assets/aa389bfa-1638-4d9e-9395-bd0e4ecda8c0)  
[Category View](https://github.com/user-attachments/assets/5a3e330f-310f-4fed-bd33-23f3b98af138)  
[Search Result](https://github.com/user-attachments/assets/20dbb36f-5ae0-4742-8474-9d2d75b16852)  
[Login Page](https://github.com/user-attachments/assets/82cdb466-de1b-426c-a7cd-b0b612f4a080)  
[Product Detail](https://github.com/user-attachments/assets/f0f5bcb1-eaf1-48dc-860e-c6889713121f)  
[Cart](https://github.com/user-attachments/assets/86d5b7a6-4f89-407b-b325-c82428d47f5f)  
[Profile](https://github.com/user-attachments/assets/9ca0833a-627a-4249-b00e-98539bb49f4e)  
[Payment Page](https://github.com/user-attachments/assets/bba440eb-4ad9-4be1-a4d8-0c88e87d60d7)  
[Payment Confirmation](https://github.com/user-attachments/assets/83b9e76e-5edd-4016-89f6-ef67675eff37)  
[MyOrder List](https://github.com/user-attachments/assets/4cf6cd8f-af9b-4470-b6d3-33a498bd4f72)  
[Order Details](https://github.com/user-attachments/assets/8821d844-212b-4b62-86d9-9a5bce342d06)  
[Order Tracking](https://github.com/user-attachments/assets/317d6b83-0768-4202-a384-f80ce09d7333)  
[Invoice](https://github.com/user-attachments/assets/b5df326b-c1cb-4f81-b1a5-87d656c24d56)  


### 📊 Admin Images


[Admin Dashboard](https://github.com/user-attachments/assets/a86817e8-6fae-40d3-bda0-0b780dfb311d)  
[Graph Dashboard](https://github.com/user-attachments/assets/7cd20a8a-f48e-4094-b2c1-2e991ad33d76)  
[Sale Chart](https://github.com/user-attachments/assets/1a5af3ff-933b-4a87-bdd1-3a30acae9371)  
[Tracking Chart](https://github.com/user-attachments/assets/65f53a09-ceb7-4a9a-abaf-41b62e8d91ab)  
[Manage Category](https://github.com/user-attachments/assets/c5813c6d-e2ba-444e-9f03-d6fadef18c05)  
[Preparing Details](https://github.com/user-attachments/assets/a22deb29-ef97-48ea-8432-417845b9a6dc)  
[Cancelled Booking List](https://github.com/user-attachments/assets/62cd5bc2-283e-4412-a656-8964fff77d0f)  
[Complain Details](https://github.com/user-attachments/assets/abeedb12-b835-487b-9aea-9c1ad5e64b03)  
[Delivered Order by Postman](https://github.com/user-attachments/assets/fc08d183-0925-4589-966b-9e0b83744f6a)  
[Manage Reviews](https://github.com/user-attachments/assets/0f276575-2983-4d91-84d9-536649fea9e1)  
[Front-end Content](https://github.com/user-attachments/assets/0f13387b-e803-4220-8e5c-261a421ebe10)  
