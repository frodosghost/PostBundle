# AGB News Bundle
This bundle has been created to allow editing and display of News Items for the AGB Group web suite.

## Installation
If you are using Symfony 2.1 you can install by adding the dependencies into the `composer.json` file.

    "require": {
        ...
        "agb/news-bundle": "dev-master"        
    },
    "repositories": [
       {
           "type": "package",
           "package": {
               "version": "dev-master",
               "name": "agb/news-bundle",
               "source": {
                   "url": "git@bitbucket.org:frodosghost/agbnewsbundle.git",
                   "type": "git",
                   "reference": "master"
               },
               "dist": {
                   "url": "https://bitbucket.org/frodosghost/agbnewsbundle/get/master.zip",
                   "type": "zip"
               }
           }
       }
    ]
