<div class="d-flex align-items-start gap-3 bg-white mb-2 p-3 rounded">
    <div>
        <a href="/doctor-preview/<?=esc($username);?>" class="avatar avatar-sm mr-2">
            <img src="<?=esc($profileImage);?>" alt="User Image" width="50" height="50">
        </a>
    </div>
    <div class="doc-info-cont-appointment fs-6 fw-medium text-dark">
        <h4 class="m-0 p-0 text-body">
            <a href="/doctor-preview/<?=esc($username) ?>">
                <?=esc($dr);?> <?=esc($name);?>
            </a>
        </h4>
        <span class="text-muted"><?=esc($specialization);?></span>
    </div>
</div>
