pipeline {
    agent any
    
    environment {
        APP_NAME = 'php-jenkins-demo'
        DOCKER_IMAGE = "${APP_NAME}:${BUILD_NUMBER}"
        DOCKER_CONTAINER = "${APP_NAME}-container"
        APP_ENV = 'staging'
    }
    
    stages {
        stage('Clone Repository') {
            steps {
                echo '🔄 Cloning repository...'
                git branch: 'main', url: 'https://github.com/YOUR_USERNAME/php-jenkins-demo.git'
                
                echo '📋 Repository cloned successfully'
                sh 'ls -la'
            }
        }
        
        stage('Install Dependencies') {
            steps {
                echo '📦 Installing PHP dependencies...'
                script {
                    // Install Composer dependencies
                    sh '''
                        if [ -f "composer.json" ]; then
                            docker run --rm -v $(pwd):/app composer:latest install --no-dev --optimize-autoloader
                            echo "✅ Composer dependencies installed"
                        else
                            echo "⚠️  No composer.json found, skipping dependency installation"
                        fi
                    '''
                }
            }
        }
        
        stage('Code Quality Check') {
            steps {
                echo '🔍 Running code quality checks...'
                script {
                    // Basic PHP syntax check
                    sh '''
                        echo "Checking PHP syntax..."
                        find . -name "*.php" -exec php -l {} \\; | grep -v "No syntax errors"
                        
                        echo "✅ PHP syntax check completed"
                    '''
                }
            }
        }
        
        stage('Run Unit Tests') {
            steps {
                echo '🧪 Running unit tests...'
                script {
                    try {
                        sh '''
                            # Install dev dependencies for testing
                            docker run --rm -v $(pwd):/app composer:latest install
                            
                            # Run PHPUnit tests
                            docker run --rm -v $(pwd):/app -w /app php:8.1-cli ./vendor/bin/phpunit --configuration phpunit.xml --coverage-text
                        '''
                        echo '✅ All tests passed!'
                    } catch (Exception e) {
                        echo "❌ Tests failed: ${e.getMessage()}"
                        currentBuild.result = 'UNSTABLE'
                    }
                }
            }
            post {
                always {
                    // Archive test results if available
                    script {
                        if (fileExists('coverage')) {
                            archiveArtifacts artifacts: 'coverage/**/*', allowEmptyArchive: true
                        }
                    }
                }
                success {
                    echo '✅ Unit tests completed successfully'
                }
                failure {
                    echo '❌ Unit tests failed'
                }
            }
        }
        
        stage('Build Docker Image') {
            steps {
                echo '🐳 Building Docker image...'
                script {
                    try {
                        sh """
                            docker build -t ${DOCKER_IMAGE} .
                            docker tag ${DOCKER_IMAGE} ${APP_NAME}:latest
                            echo "✅ Docker image built successfully: ${DOCKER_IMAGE}"
                        """
                    } catch (Exception e) {
                        echo "❌ Docker build failed: ${e.getMessage()}"
                        error("Docker build failed")
                    }
                }
            }
        }
        
        stage('Security Scan') {
            steps {
                echo '🔒 Running security scan...'
                script {
                    try {
                        // Basic security check - scan for common vulnerabilities
                        sh '''
                            echo "Scanning for potential security issues..."
                            
                            # Check for common security issues in PHP files
                            echo "Checking for eval() usage..."
                            if grep -r "eval(" --include="*.php" .; then
                                echo "⚠️  Warning: eval() found in code"
                            else
                                echo "✅ No eval() usage found"
                            fi
                            
                            # Check for SQL injection patterns
                            echo "Checking for potential SQL injection..."
                            if grep -r "\\$_[GET|POST].*SELECT\\|INSERT\\|UPDATE\\|DELETE" --include="*.php" .; then
                                echo "⚠️  Warning: Potential SQL injection vulnerability found"
                            else
                                echo "✅ No obvious SQL injection patterns found"
                            fi
                            
                            echo "✅ Basic security scan completed"
                        '''
                    } catch (Exception e) {
                        echo "⚠️  Security scan encountered issues: ${e.getMessage()}"
                    }
                }
            }
        }
        
        stage('Deploy to Staging') {
            steps {
                echo '🚀 Deploying to staging environment...'
                script {
                    try {
                        sh """
                            # Stop and remove existing container if it exists
                            docker stop ${DOCKER_CONTAINER} || true
                            docker rm ${DOCKER_CONTAINER} || true
                            
                            # Run new container
                            docker run -d \\
                                --name ${DOCKER_CONTAINER} \\
                                -p 8081:80 \\
                                -e APP_ENV=${APP_ENV} \\
                                ${DOCKER_IMAGE}
                            
                            # Wait for container to be ready
                            sleep 5
                            
                            # Health check
                            curl -f http://localhost:8081/ || exit 1
                        """
                    } catch (Exception e) {
                        echo "❌ Deployment failed: ${e.getMessage()}"
                        error("Deployment failed")
                    }
                }
            }
        }
        
        stage('Integration Tests') {
            steps {
                echo '🔗 Running integration tests...'
                script {
                    try {
                        sh '''
                            # Test if application is responding
                            echo "Testing application endpoints..."
                            
                            # Test main page
                            if curl -f -s http://localhost:8081/ | grep -q "Aplikasi PHP Sederhana"; then
                                echo "✅ Main page is working"
                            else
                                echo "❌ Main page test failed"
                                exit 1
                            fi
                            
                            # Test if PHP is working
                            if curl -f -s http://localhost:8081/ | grep -q "PHP Version"; then
                                echo "✅ PHP is working correctly"
                            else
                                echo "❌ PHP test failed"
                                exit 1
                            fi
                            
                            echo "✅ Integration tests passed"
                        '''
                    } catch (Exception e) {
                        echo "❌ Integration tests failed: ${e.getMessage()}"
                        currentBuild.result = 'UNSTABLE'
                    }
                }
            }
        }
    }
    
    post {
        always {
            echo '🧹 Cleaning up...'
            script {
                // Clean up old Docker images (keep last 3 builds)
                sh '''
                    echo "Cleaning up old Docker images..."
                    docker images ${APP_NAME} --format "table {{.Repository}}:{{.Tag}}" | tail -n +2 | head -n -3 | xargs -r docker rmi || true
                '''
            }
        }
        success {
            echo '''
            ✅ Pipeline completed successfully!
            
            📊 Summary:
            - ✅ Code cloned
            - ✅ Dependencies installed  
            - ✅ Tests passed
            - ✅ Docker image built
            - ✅ Security scan completed
            - ✅ Application deployed
            - ✅ Integration tests passed
            
            🌐 Application is running at: http://localhost:8081
            '''
        }
        failure {
            echo '❌ Pipeline failed!'
            sh 'docker stop ${DOCKER_CONTAINER} || true'
            sh 'docker rm ${DOCKER_CONTAINER} || true'
        }
        unstable {
            echo '⚠️  Pipeline completed with warnings. Check the logs for details.'
        }
    }
}
