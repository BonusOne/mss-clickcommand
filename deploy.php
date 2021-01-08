<?php
namespace Deployer;

require 'recipe/symfony.php';
require 'recipe/composer.php';
set('user', function () {
    return runLocally('git config --get user.name');
});

// Project name
set('application', 'mss_cc');

// Project repository
//set('repository', 'https://github.com/BonusOne/mss-clickcommand');
set('repository', 'git@github.com:BonusOne/mss-clickcommand.git');
set('branch', 'master');

// [Optional] Allocate tty for git clone. Default value is false.
set('git_tty', false); 

// Shared files/dirs between deploys 
add('shared_files', ['.env']);
add('shared_dirs', ['vendor','var/log']);

// Writable dirs by web server 
add('writable_dirs', []);
set('allow_anonymous_stats', false);
set('writable_mode', 'chmod');

// Hosts
 
    
host('prod')
    ->hostname('mss-cc.loc')
    ->multiplexing(false)
    ->user('root')
    ->set('branch', 'master')
    ->identityFile('~/.ssh/id_rsa')
    ->set('deploy_path', '/var/www/html/{{application}}');
    
// Tasks

task('build', function () {
    run('cd {{release_path}} && build');
});

task('database:migrate', function () {
    run("cd {{release_path}} && php bin/console doctrine:schema:update --force");
});

/*task('assets', function () {
    run("cd {{release_path}} && {{bin/yarn}} encore production");
});*/

/*set('bin/yarn', function () {
    return run('which yarn');
});*/

/*desc('Install Yarn packages');
task('yarn:install', function () {
    if (has('previous_release')) {
        if (test('[ -d {{previous_release}}/node_modules ]')) {
            run('cp -R {{previous_release}}/node_modules {{release_path}}');
        }
    }
    run("cd {{release_path}} && {{bin/yarn}} install");
});*/

// [Optional] if deploy fails automatically unlock.
after('deploy:failed', 'deploy:unlock');
//after('deploy:writable', 'assets');

//after('deploy:update_code', 'yarn:install');
// Migrate database before symlink new release.

before('deploy:symlink', 'database:migrate');

