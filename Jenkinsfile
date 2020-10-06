pipeline {
  agent any
  stages {
    stage('composer_install') {
      steps {
        sh '''sh \'composer update\'
'''
      }
    }

    stage('test') {
      steps {
        sh '''
        sh \'vendor/bin/phpunit\''''
      }
    }

  }
}