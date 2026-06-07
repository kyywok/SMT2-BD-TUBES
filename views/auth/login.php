<<<<<<< HEAD
<?php include 'views/templates/header.php'; ?>
<div class="row justify-content-center mt-5">
    <div class="col-md-4">
        <div class="card">
            <div class="card-header bg-dark text-white text-center">
                <h4>Admin Login</h4>
            </div>
            <div class="card-body">
                <form action="index.php?controller=auth&action=login" method="POST">
                    <div class="mb-3">
                        <label>Email</label>
                        <input type="email" name="email" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label>Password</label>
                        <input type="password" name="password" class="form-control" required>
                    </div>
                    <button type="submit" class="btn btn-dark w-100">Login</button>
                </form>
            </div>
        </div>
    </div>
</div>
=======
<?php include 'views/templates/header.php'; ?>
<div class="row justify-content-center mt-5">
    <div class="col-md-4">
        <div class="card">
            <div class="card-header bg-dark text-white text-center">
                <h4>Admin Login</h4>
            </div>
            <div class="card-body">
                <form action="index.php?controller=auth&action=login" method="POST">
                    <div class="mb-3">
                        <label>Email</label>
                        <input type="email" name="email" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label>Password</label>
                        <input type="password" name="password" class="form-control" required>
                    </div>
                    <button type="submit" class="btn btn-dark w-100">Login</button>
                </form>
            </div>
        </div>
    </div>
</div>
>>>>>>> master
<?php include 'views/templates/footer.php'; ?>