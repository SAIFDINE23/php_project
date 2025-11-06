pipeline {
    agent any

    environment {
        DOCKERHUB_CREDENTIALS = credentials('dockerhub-credentials')
        IMAGE_NAME = "saifdine23/gestiondesemployes-web"
        IMAGE_TAG = "latest"
        GIT_REPO = "https://github.com/SAIFDINE23/php_project.git"
        DEPLOY_DIR = "k8s"
        KUBECONFIG = "/var/jenkins_home/.kube/config"
    }

    stages {
        stage('Pull GitHub') {
            steps {
                git branch: 'main', url: "${GIT_REPO}"
            }
        }

        stage('Install Node.js dependencies') {
            steps {
                sh 'npm install'
            }
        }

        stage('Run Node.js Tests') {
            steps {
                echo "🧪 Lancement des tests / linter Node.js..."
                sh 'npm run lint'
                sh 'npm run test'
            }
        }

        stage('Build Docker Image') {
            steps {
                echo "🐳 Construction de l’image Docker..."
                script {
                    docker.build("${IMAGE_NAME}:${IMAGE_TAG}")
                }
            }
        }

        stage('Push to DockerHub') {
            steps {
                docker.withRegistry('https://index.docker.io/v1/', "${DOCKERHUB_CREDENTIALS}") {
                    docker.image("${IMAGE_NAME}:${IMAGE_TAG}").push()
                }
            }
        }

        stage('Deploy to Kubernetes') {
            steps {
                withKubeConfig(caCertificate: '', clusterName: 'minikube', contextName: 'minikube', credentialsId: 'kubernetes-jenkins-secret', serverUrl: 'https://192.168.49.2:8443') {
                    sh "kubectl apply -f ${DEPLOY_DIR}/mysql-pvc.yaml"
                    sh "kubectl apply -f ${DEPLOY_DIR}/mysql-deployment.yaml"
                    sh "kubectl apply -f ${DEPLOY_DIR}/php-deployment.yaml"
                    sh "kubectl rollout status deployment/php-app"
                }
            }
        }
    }

    post {
        success {
            echo "✅ Pipeline terminé avec succès !"
        }
        failure {
            echo "❌ Échec du pipeline. Vérifie les logs."
        }
    }
}
