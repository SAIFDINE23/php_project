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
                echo "📦 Clonage du dépôt GitHub..."
                git branch: 'main', url: "${GIT_REPO}"
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
                echo "🚀 Envoi de l’image sur DockerHub..."
                script {
                    docker.withRegistry('https://index.docker.io/v1/', "${DOCKERHUB_CREDENTIALS}") {
                        docker.image("${IMAGE_NAME}:${IMAGE_TAG}").push()
                    }
                }
            }
        }

        stage('Deploy to Kubernetes') {
            steps {
                echo "⚙️ Déploiement sur Kubernetes..."
                sh """
                kubectl config use-context minikube
                kubectl apply -f ${DEPLOY_DIR}/mysql-pvc.yaml
                kubectl apply -f ${DEPLOY_DIR}/mysql-deployment.yaml
                kubectl apply -f ${DEPLOY_DIR}/php-deployment.yaml
                kubectl rollout status deployment/php-app
                kubectl get pods -o wide
                """
            }
        }

        stage('Run Application') {
            steps {
                echo "🌐 Récupération de l’URL Minikube..."
                script {
                    def appUrl = sh(script: 'minikube service php-app --url', returnStdout: true).trim()
                    echo "Application accessible sur : ${appUrl}"
                }
            }
        }
    }

    post {
        success {
            echo "✅ Déploiement réussi sur Kubernetes !"
        }
        failure {
            echo "❌ Le pipeline a échoué. Vérifie les logs Jenkins."
        }
    }
}
