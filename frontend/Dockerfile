# Version demandée (Current en 2026)
FROM node:25.8.1-alpine

# Force l'installation de la version exacte de npm
RUN npm install -g npm@11.11.0

WORKDIR /app

# On installe les dépendances en premier pour profiter du cache Docker
COPY package*.json ./
RUN npm install

COPY . .

EXPOSE 5173
# --host est nécessaire pour que Docker puisse rediriger le trafic
CMD ["npm", "run", "dev", "--", "--host"]