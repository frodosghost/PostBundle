# Manhattan Posts Bundle
This bundle has been created to allow editing and display of News, Articles or Posts Items. The idea is simple created data that is associated by Categories and by dates that publishes new Posts.

## Installation
If you are using Symfony 2.1 you can install by adding the dependencies into the `composer.json` file.

    "require": {
        ...
        "manhattan/posts-bundle": "dev-master"        
    },
    [{
        "type": "package",
        "package": {
            "version": "dev-master",
            "name": "manhattan/posts-bundle",
            "source": {
                "url": "git@bitbucket.org:frodosghost/postsbundle.git",
                "type": "git",
                "reference": "master"
            },
            "autoload": {
                "psr-0": { "Manhattan\\Bundle\\PostsBundle": "" }
            },
            "target-dir": "Manhattan/Bundle/PostsBundle"
        }
    }]
