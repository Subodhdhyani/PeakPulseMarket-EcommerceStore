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

[Homepage](https://github.com/user-attachments/assets/17a214ab-af3a-4a66-b3a1-f40d34706fc4)  
[Homepage Small Screen](https://github.com/user-attachments/assets/8e66fdb8-8dbe-4d74-b389-b94a81018873)  
[Category View](https://github.com/user-attachments/assets/3cdfd5ef-58b2-4f02-a42f-f9aaf50526e0)  
[Search Result](https://github.com/user-attachments/assets/24b5d6dc-d56b-4174-83f1-60c214658103)  
[Login Page](https://github.com/user-attachments/assets/3fb7fe58-7043-499b-a12d-36da757c92da)  
[Product Detail](https://github.com/user-attachments/assets/000156b6-1991-4059-afb0-ba4003ad3eb6)  
[Cart](https://github.com/user-attachments/assets/6064b2ae-5bcb-4e5a-ac67-5ef69fedc35f)  
[Profile](https://github.com/user-attachments/assets/ecb77c2c-d86e-44ad-8075-fbd10d7d1782)  
[Payment Page](https://github.com/user-attachments/assets/950c6079-811d-4bcf-9f92-94f5e6e26f6f)  
[Payment Confirmation](https://github.com/user-attachments/assets/c8ede25e-5d77-4b90-87d8-a3e9cf81d51a)  
[MyOrder List](https://github.com/user-attachments/assets/e8a42f7c-eef9-489e-8a94-da2962fd7c0c)  
[Order Details](https://github.com/user-attachments/assets/89181674-bf98-415d-bb0b-8d5725a17903)  
[Order Tracking](https://github.com/user-attachments/assets/d36e35e0-601b-4501-b59c-b109de89f753)  
[Invoice](https://github.com/user-attachments/assets/50de7532-aae2-45d6-b487-a7bde779d31f)   

### 📊 Admin Images

[Admin Dashboard](https://github.com/user-attachments/assets/1cc959f5-7c71-46ad-8110-034737f9c764)  
[Graph Dashboard](https://github.com/user-attachments/assets/888a868a-8b3d-49cc-9ac7-1a96d90ea2ca)  
[Sale Chart](https://github.com/user-attachments/assets/6a7d2cbf-509b-43a0-bad7-d6139fe34263)  
[Tracking Chart](https://github.com/user-attachments/assets/01c14c26-b555-4d6c-99a2-49429e743afb)  
[Manage Category](https://github.com/user-attachments/assets/fdf9f90e-ed16-41ab-9fe1-656858daab5f)  
[Preparing Details](https://github.com/user-attachments/assets/b3ecbeaa-68ca-4187-82df-6a5adf3f2483)  
[Cancelled Booking List](https://github.com/user-attachments/assets/1f008c18-3585-4db0-8fdc-a1cc6da022b7)  
[Complain Details](https://github.com/user-attachments/assets/eb3578d8-db2f-4d50-85a2-08e7df6a4325)  
[Delivered Order by Postman](https://github.com/user-attachments/assets/22f84e6e-4fec-44bc-a9e4-525c4fbc3794)  
[Manage Reviews](https://github.com/user-attachments/assets/988e03a6-9e2e-4028-be91-3e2431020f52)  
[Front-end Content](https://github.com/user-attachments/assets/a076b5aa-1f9d-4b5a-8643-9e10a8ddb4d7)  
