pipeline {
    agent any

    triggers {
        pollSCM('H/2 * * * *')
    }

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
                sh 'docker compose -f docker-compose.yml build'
            }
        }

        stage('Check PHP Syntax') {
            steps {
                echo 'Checking PHP syntax inside the built image...'
                sh 'docker run --rm workspace-web php -l /var/www/html/index.php'
            }
        }

        stage('Deploy Application') {
            steps {
                echo 'Deploying application using Docker...'
                sh 'docker rm -f sum_app_web sum_app_db unix_homework-web-1 unix_homework-db-1 || true'
                sh 'docker compose -f docker-compose.yml down --remove-orphans -v || true'
                sh 'docker compose -f docker-compose.yml up -d --force-recreate'
            }
        }

        stage('Initialize Database') {
            steps {
                echo 'Waiting for MySQL to be ready and applying schema...'
                sh '''
                    for i in $(seq 1 60); do
                        if docker exec sum_app_db mysql -usumuser -p12345 sum_app -e "SELECT 1" >/dev/null 2>&1; then
                            echo "MySQL accepts sumuser logins."
                            break
                        fi
                        echo "Waiting for MySQL user setup... ($i/60)"
                        sleep 2
                    done
                    docker cp db.sql sum_app_db:/tmp/db.sql
                    docker exec sum_app_db sh -c 'mysql -usumuser -p12345 sum_app < /tmp/db.sql'
                    echo "Database schema applied."
                '''
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
