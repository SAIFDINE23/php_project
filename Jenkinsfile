pipeline {
    agent any

    environment {
        DOCKERHUB_CREDENTIALS = credentials('dockerhub-credentials')
        IMAGE_NAME = "saifdine23/gestiondesemployes-web"
        IMAGE_TAG = "latest"
        GIT_REPO = "https://github.com/SAIFDINE23/php_project.git"
        DEPLOY_DIR = "k8s"
    }

    stages {

        stage('Pull GitHub') {
            steps {
                echo "📦 Clonage du dépôt GitHub..."
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
                    sh "docker build -t ${IMAGE_NAME}:${IMAGE_TAG} ."
                }
            }
        }

        stage('Push to DockerHub') {
            steps {
                echo "🚀 Envoi de l’image sur DockerHub..."
                script {
                    sh """
                    echo "$DOCKERHUB_CREDENTIALS_PSW" | docker login -u "$DOCKERHUB_CREDENTIALS_USR" --password-stdin
                    docker push ${IMAGE_NAME}:${IMAGE_TAG}
                    """
                }
            }
        }

        stage('Deploy to Kubernetes') {
            steps {
                echo "⚙️ Déploiement sur Kubernetes..."
                withKubeConfig(
                    caCertificate: '',
                    clusterName: 'minikube',
                    contextName: 'minikube',
                    credentialsId: 'kubernetes-jenkins-secret',
                    namespace: '',
                    restrictKubeConfigAccess: false,
                    serverUrl: 'https://192.168.49.2:8443'
                ) {
                    sh "kubectl get nodes"
                    sh "kubectl apply -f ${DEPLOY_DIR}/mysql-pvc.yaml"
                    sh "kubectl apply -f ${DEPLOY_DIR}/mysql-deployment.yaml"
                    sh "kubectl apply -f ${DEPLOY_DIR}/php-deployment.yaml"
                    sh "kubectl rollout status deployment/php-app"
                    sh "kubectl get pods -o wide"
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
