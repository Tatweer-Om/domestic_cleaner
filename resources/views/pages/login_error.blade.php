<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Access Denied</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light d-flex align-items-center justify-content-center vh-100">

  <div class="card shadow-lg text-center p-4" style="max-width: 500px; border-radius: 20px;">
    <div class="card-body">
      <h1 class="text-danger mb-3">🚫</h1>
      
      <!-- English -->
      <h3 class="fw-bold text-danger">Access Denied</h3>
      <p class="mb-4">You do not have permission to access this page. Please login with the correct account.</p>
      
      <!-- Arabic -->
      <h3 class="fw-bold text-danger">🚫 الوصول مرفوض</h3>
      <p>لا تملك صلاحية الدخول إلى هذه الصفحة. يرجى تسجيل الدخول بالحساب الصحيح.</p>

      <a href="{{url('login_page')}}" class="btn btn-primary mt-3">🔑 Go to Login</a>
    </div>
  </div>

</body>
</html>
