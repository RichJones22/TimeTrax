@servers(['web' => 'deploy-DigitalOcean'])

<?php
$repo = 'https://github.com/RichJones22/TimeTrax.git';
$release_dir = '/var/www/TimeTraxDeploy/releases';
$app_dir = '/var/www/TimeTraxDeploy/TimeTrax';
$release = 'release_' . date('YmdHis');
?>

@macro('deploy', ['on' => 'web'])
    fetch_repo
    run_composer
    update_permissions
    update_symlinks
@endmacro

@task('fetch_repo')
    [ -d {{ $release_dir }} ] || mkdir -p {{ $release_dir }};
    cd {{ $release_dir }};
    git clone -b master {{ $repo }} {{ $release }};
@endtask

@task('run_composer')
    cd {{ $release_dir }}/{{ $release }};
    composer install --prefer-dist --no-scripts;
    php artisan clear-compiled --env=production;
    php artisan optimize --env=production;
@endtask

@task('update_permissions')
    cd {{ $release_dir }};
    chgrp -R root {{ $release }};
    chmod -R ug+rwx {{ $release }};
@endtask

@task('update_symlinks')
    ln -nfs {{ $release_dir }}/{{ $release }} {{ $app_dir }};
    chgrp -h root {{ $app_dir }};

    cd {{ $release_dir }}/{{ $release }};
    ln -nfs ../../.env .env;
    chgrp -h root .env;

    chmod -R 777 {{ $release_dir }}/{{ $release }}/storage;

    sudo service php7.0-fpm reload;
@endtask
