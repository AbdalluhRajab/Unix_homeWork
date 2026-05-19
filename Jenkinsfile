pipeline {
    agent any

    stages {
        stage('Pull Code') {
            steps {
                echo 'Pulling code from GitHub...'
                checkout scm
            }
        }

        stage('Build Docker Containers') {
            steps {
                echo 'Building Docker containers...'
                sh 'docker compose build'
            }
        }

        stage('Check PHP Syntax') {
            steps {
                echo 'Checking PHP syntax inside the built image...'
                sh 'docker compose run --rm --no-deps web php -l index.php'
            }
        }

        stage('Deploy Application') {
            steps {
                echo 'Deploying application using Docker...'
                sh 'docker rm -f sum_app_web sum_app_db || true'
                sh 'docker compose down --remove-orphans || true'
                sh 'docker compose up -d --force-recreate'
            }
        }

        stage('Verify Containers') {
            steps {
                echo 'Checking running containers...'
                sh 'docker ps'
            }
        }
    }
}
