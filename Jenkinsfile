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
                sh 'docker compose down --remove-orphans -v || true'
                sh 'docker compose up -d --force-recreate'
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
                    docker exec -i sum_app_db mysql -usumuser -p12345 sum_app < db.sql
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
