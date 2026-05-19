pipeline {
    agent any

    stages {
        stage('Pull Code') {
            steps {
                echo 'Pulling code from GitHub...'
                checkout scm
            }
        }

        stage('Check PHP Syntax') {
            steps {
                echo 'Checking PHP syntax...'
                sh 'php -l index.php'
            }
        }

        stage('Build Docker Containers') {
            steps {
                echo 'Building Docker containers...'
                sh 'docker compose build'
            }
        }

     stage('Deploy Application') {
    steps {
        echo 'Deploying application using Docker...'
        sh 'docker rm -f sum_app_web sum_app_db || true'
        sh 'docker compose down || true'
        sh 'docker compose up -d --build'
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