# User Management System Documentation

## 📋 Tổng Quan

Hệ thống quản lý người dùng hoàn chỉnh cho cả Admin và Client với các tính năng quản lý đầy đủ (CRUD).

## 🗂️ Cấu Trúc Dự Án

### Controllers
```
app/Http/Controllers/
├── Admin/
│   ├── DashboardController.php      # Dashboard quản lý
│   ├── ProductController.php        # Quản lý sản phẩm
│   └── UserController.php           # ✨ Quản lý người dùng
└── Client/
    └── ProfileController.php        # ✨ Quản lý hồ sơ client
```

### Views
```
resources/views/
├── admin/
│   └── users/
│       ├── index.blade.php          # ✨ Danh sách người dùng
│       ├── show.blade.php           # ✨ Chi tiết người dùng
│       └── form.blade.php           # ✨ Form tạo/sửa người dùng
└── client/
    └── account.blade.php            # ✨ Quản lý tài khoản client
```

## 🛣️ Routing Configuration

### Admin User Management Routes
Protected by `auth` and `is_admin` middleware

| Method | URI | Action | Tên Route |
|--------|-----|--------|-----------|
| GET | `/admin/users` | List users | `admin.users.index` |
| GET | `/admin/users/create` | Show create form | `admin.users.create` |
| POST | `/admin/users` | Store user | `admin.users.store` |
| GET | `/admin/users/{user}` | Show user details | `admin.users.show` |
| GET | `/admin/users/{user}/edit` | Show edit form | `admin.users.edit` |
| PUT | `/admin/users/{user}` | Update user | `admin.users.update` |
| DELETE | `/admin/users/{user}` | Delete user | `admin.users.destroy` |

### Client Profile Routes
Protected by `auth` middleware

| Method | URI | Action | Tên Route |
|--------|-----|--------|-----------|
| GET | `/page/account` | Show profile | `client.account` |
| PUT | `/page/account` | Update profile | `client.account.update` |

## 📊 Database Schema

### Users Table
```sql
- id (primary key)
- name (string)
- email (string, unique)
- email_verified_at (timestamp, nullable)
- password (string)
- phone (string, nullable)
- address (text, nullable)
- role (enum: 'user', 'admin')
- remember_token (string, nullable)
- created_at (timestamp)
- updated_at (timestamp)
```

## 🎯 Tính Năng Chính

### 1. Admin User Management
**Xem Danh Sách Người Dùng** (`/admin/users`)
- Hiển thị tất cả người dùng với phân trang (15 người/trang)
- Hiển thị: ID, Tên, Email, Điện thoại, Vai trò
- Hành động: Xem, Chỉnh sửa, Xóa

**Tạo Người Dùng Mới** (`/admin/users/create`)
- Form tạo user với các trường:
  - Tên (bắt buộc)
  - Email (bắt buộc, duy nhất)
  - Mật khẩu (bắt buộc, tối thiểu 6 ký tự)
  - Điện thoại (tùy chọn)
  - Địa chỉ (tùy chọn)
  - Vai trò: User hoặc Admin
- Xác thực dữ liệu phía server
- Thông báo thành công

**Xem Chi Tiết Người Dùng** (`/admin/users/{id}`)
- Thông tin tài khoản: Tên, Email, Vai trò
- Thông tin liên hệ: Điện thoại, Email xác minh
- Địa chỉ
- Sidebar:
  - Avatar người dùng
  - Tổng số đơn hàng
  - Đơn hàng hoàn thành
  - Ngày tạo & cập nhật

**Chỉnh Sửa Người Dùng** (`/admin/users/{id}/edit`)
- Form tương tự tạo mới
- Mật khẩu là tùy chọn (nếu để trống, không đổi)
- Xác thực dữ liệu
- Thông báo cập nhật thành công

**Xóa Người Dùng**
- Modal xác nhận trước khi xóa
- Xóa vĩnh viễn khỏi hệ thống

### 2. Client Profile Management
**Xem Hồ Sơ Cá Nhân** (`/page/account`)
- Thông tin tài khoản: Tên, Email
- Thông tin liên hệ: Điện thoại
- Địa chỉ
- Đổi mật khẩu (tùy chọn)
- Lịch sử đơn hàng:
  - Mã đơn hàng
  - Ngày đặt
  - Tổng tiền
  - Trạng thái (Chờ xử lý / Hoàn thành)

**Cập Nhật Thông Tin** 
- Cập nhật các trường cá nhân
- Đổi mật khẩu nếu cần
- Xác thực dữ liệu
- Thông báo cập nhật thành công

## 💻 Cách Sử Dụng Controllers

### Admin UserController

```php
// Xem danh sách người dùng
public function index(): View
// Trả về danh sách người dùng phân trang

// Hiển thị form tạo mới
public function create(): View

// Lưu người dùng mới
public function store(Request $request): RedirectResponse
// Validate và tạo user mới

// Xem chi tiết người dùng
public function show(User $user): View

// Hiển thị form chỉnh sửa
public function edit(User $user): View

// Cập nhật người dùng
public function update(Request $request, User $user): RedirectResponse

// Xóa người dùng
public function destroy(User $user): RedirectResponse
```

### Client ProfileController

```php
// Xem hồ sơ cá nhân
public function show(): View
// Lấy thông tin user hiện tại

// Cập nhật thông tin
public function update(Request $request): RedirectResponse
// Cập nhật thông tin và mật khẩu (nếu có)
```

## 🔐 Validation Rules

### Create User (Admin)
```php
[
    'name' => 'required|string|max:255',
    'email' => 'required|email|unique:users',
    'password' => 'required|min:6|confirmed',
    'phone' => 'nullable|string|max:20',
    'address' => 'nullable|string|max:255',
    'role' => 'required|in:user,admin',
]
```

### Update User (Admin)
```php
[
    'name' => 'required|string|max:255',
    'email' => 'required|email|unique:users,email,' . $user->id,
    'phone' => 'nullable|string|max:20',
    'address' => 'nullable|string|max:255',
    'role' => 'required|in:user,admin',
    'password' => 'nullable|min:6|confirmed', // Nếu có password
]
```

### Update Profile (Client)
```php
[
    'name' => 'required|string|max:255',
    'email' => 'required|email|unique:users,email,' . $user->id,
    'phone' => 'nullable|string|max:20',
    'address' => 'nullable|string|max:255',
    'password' => 'nullable|min:6|confirmed', // Nếu có password
]
```

## 🎨 UI/UX Features

### Admin Panel
- **Cards & Badges**: Hiển thị trạng thái vai trò (Admin/User)
- **Icons**: Bootstrap Icons cho dễ nhận biết
- **Responsive**: Mobile-friendly design
- **Modals**: Xác nhận xóa trước khi thực hiện
- **Pagination**: Phân trang 15 bản ghi/trang
- **Success Alerts**: Thông báo thành công/lỗi

### Client Account
- **Sidebar Navigation**: Menu trái để điều hướng
- **Tab Sections**: Chia theo chuyên đề (Account, Contact, Address, etc.)
- **Order History**: Bảng danh sách đơn hàng
- **Status Badges**: Trạng thái đơn hàng rõ ràng

## 📝 Thêm Vào Các Tệp Hiện Có

Để tích hợp hoàn toàn, cần:

1. **routes/web.php** - ✅ Đã cập nhật
2. **app/Models/User.php** - ✅ Đã có
3. **Database Migrations** - ✅ Đã có các bảng cần thiết

## 🚀 Mở Rộng trong Tương Lai

### Có thể thêm:
- Xác thực email (Email Verification)
- Reset mật khẩu quên
- 2FA (Two-Factor Authentication)
- Lịch sử hoạt động người dùng
- Export danh sách user ra Excel/PDF
- Filter & Search nâng cao
- Bulk actions (Xóa nhiều user)
- Phân quyền chi tiết hơn

## 🐛 Troubleshooting

### User model binding không hoạt động
Đảm bảo User model có `web` route model binding trong `RouteServiceProvider`

### Form validation không hiển thị
Kiểm tra view có sử dụng `@error('field_name')` không

### Middleware is_admin không được tìm thấy
Tạo file `app/Http/Middleware/IsAdmin.php` nếu chưa có

## 📚 Tài Liệu Tham Khảo

- [Laravel Documentation](https://laravel.com/docs)
- [Blade Templates](https://laravel.com/docs/blade)
- [Form Requests Validation](https://laravel.com/docs/validation)
- [Route Model Binding](https://laravel.com/docs/routing#route-model-binding)
