@servers(['web' => 'ghi-dev'])

@setup
    date_default_timezone_set('America/Mexico_City');

    if (! isset($branch) ) {
        throw new \InvalidArgumentException("Debe especificar la rama del repositorio (develop|master)");
    }

    $repo = 'git_sao@172.20.73.209:~/web/control-equipamiento.git';
    $release_dir = '/var/www/releases/control-equipamiento';
    $app_dir = '/var/www/current/control-equipamiento';
    $release_name = date('YmdHis');
    $release = $release_dir . '/' . $release_name;
    $env_dir = '/home/deployer/environments';
    $env_file = '.env.control-equipamiento';
    $env = $env_dir . '/' . $env_file;
    $storage_dir = '/var/www/storage/control-equipamiento';
@endsetup

@macro('deploy', ['on' => 'web'])
    fetch_repo
    prepare_storage_dir
    set_environment
    run_composer
    {{--run_npm--}}
    {{--run_bower--}}
    {{--run_gulp--}}
    optimize_files
    optimize_routes
    update_permissions
    update_symlinks
    clean_old_releases
    reload-phpfpm
@endmacro

@task('fetch_repo')
    [ -d {{ $release_dir }} ] || mkdir {{ $release_dir }};
    cd {{ $release_dir }};
    git clone {{ $repo }} -b {{ $branch }} {{ $release_name }};
@endtask

@task('prepare_storage_dir')
    [ -d {{ $storage_dir }} ] || mkdir {{ $storage_dir }};
    [ -d {{ $storage_dir }}/app ] || mkdir {{ $storage_dir }}/app;
    [ -d {{ $storage_dir }}/framework ] || mkdir {{ $storage_dir }}/framework;
    [ -d {{ $storage_dir }}/framework/cache ] || mkdir {{ $storage_dir }}/framework/cache;
    [ -d {{ $storage_dir }}/framework/sessions ] || mkdir {{ $storage_dir }}/framework/sessions;
    [ -d {{ $storage_dir }}/framework/views ] || mkdir {{ $storage_dir }}/framework/views;
    [ -d {{ $storage_dir }}/logs ] || mkdir {{ $storage_dir }}/logs;

    cd {{ $release }};
    rm -rf storage;
    ln -nfs {{ $storage_dir }} {{ $release }}/storage;
@endtask

@task('set_environment')
    cd {{ $release }};
    ln -nfs {{ $env }} {{ $release }}/.env;
@endtask

@task('run_composer')
    echo 'Installing composer dependencies';
    cd {{ $release }};
    composer install --prefer-dist --no-scripts --quiet;
@endtask

{{--@task('run_npm')--}}
    {{--cd {{ $release }};--}}
    {{--npm install;--}}
{{--@endtask--}}

{{--@task('run_bower')--}}
    {{--cd {{ $release }};--}}
    {{--bower install;--}}
{{--@endtask--}}

{{--@task('run_gulp')--}}
    {{--cd {{ $release }};--}}
    {{--npm install gulp;--}}
    {{--gulp --production;--}}
{{--@endtask--}}

@task('optimize_files')
    echo 'Optimizing files';
    cd {{ $release }};
    php artisan clear-compiled;
    php artisan optimize --force;
@endtask

@task('optimize_routes')
    echo 'Caching routes';
    cd {{ $release }};
    php artisan route:cache;
@endtask

@task('update_permissions')
    echo 'Updating permissions';
    cd {{ $release }};
    chgrp -R www-data {{ $release }};
    chmod -R ug+rwx {{ $release }};
@endtask

@task('update_symlinks')
    echo 'Updating symlinks';
    ln -nfs {{ $release }} {{ $app_dir }};
    chgrp -h www-data {{ $app_dir }};
@endtask

@task('clean_old_releases')
    echo 'Purging old releases';
    # This will list our releases by modification time and delete all but the 5 most recent.
    cd {{ $release_dir }};
    ls -1d 20* | head -n -5 | xargs -d '\n' rm -Rf
@endtask

@task('reload-phpfpm')
    echo 'Reloading php5-fpm';
    sudo service php5-fpm reload;
@endtask