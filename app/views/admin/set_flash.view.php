<?php if (isset($_SESSION['notification']['message'])): ?>
    <div class="alert border-0 bg-light-<?= $_SESSION['notification']['class'] ?> alert-dismissible fade show py-2">
        <!-- <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button> -->

        <div class="d-flex align-items-center">
            <div class="fs-3 "><i class="<?= $_SESSION['notification']['icon'] ?>"></i>
            </div>
            <div class="ms-3">
                <div class=""> <?= $_SESSION['notification']['message'] ?></div>
            </div>
        </div>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    <?php $_SESSION['notification'] = []; ?>
<?php endif; ?>