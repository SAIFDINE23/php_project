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

        stage('Build Docker Image') {
            steps {
                echo "🐳 Construction de l’image Docker..."
                script {
                    docker.build("${IMAGE_NAME}:${IMAGE_TAG}", ".")
                }
            }
        }

        stage('Push to DockerHub') {
            steps {
                echo "🚀 Envoi de l’image sur DockerHub..."
                script {
                    docker.withRegistry('https://index.docker.io/v1/', 'dockerhub-credentials') {
                        docker.image("${IMAGE_NAME}:${IMAGE_TAG}").push()
                    }
                }
            }
        }

        stage('Deploy to Kubernetes') {
            steps {
                echo "⚙️ Déploiement sur Kubernetes..."
                withEnv(["KUBECONFIG=/var/jenkins_home/.kube/config"]) {
                    sh """
                    kubectl apply -f ${DEPLOY_DIR}/mysql-pvc.yaml
                    kubectl apply -f ${DEPLOY_DIR}/mysql-deployment.yaml
                    kubectl apply -f ${DEPLOY_DIR}/php-deployment.yaml
                    kubectl get pods
                    """
                }
            }
}


        stage('Run Application') {
            steps {
                echo "🌐 Lancement du service Minikube..."
                script {
                    // Récupère et affiche l’URL du service exposé
                    sh 'minikube service php-app --url'
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
