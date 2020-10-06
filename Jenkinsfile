pipeline {
  agent any
  stages {
    stage('composer_install') {
      steps {
        sh 'composer update'
      }
    }

    stage('test') {
      steps {
        sh '''
        vendor/bin/phpunit'''
      }
    }

  }
}