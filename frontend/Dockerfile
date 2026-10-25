# Stage 1: Build Vue.js
FROM node:20-alpine AS build-stage
WORKDIR /app
COPY package*.json ./
RUN npm ci
COPY . .
RUN npm run build

# Stage 2: Serve dengan Nginx
FROM nginx:1.25-alpine AS production-stage
COPY --from=build-stage /app/dist /usr/share/nginx/html
COPY .nginx.conf /etc/nginx/conf.d/default.conf
RUN tr -d '\r' < /etc/nginx/conf.d/default.conf > /tmp/default.conf \
    && mv /tmp/default.conf /etc/nginx/conf.d/default.conf
EXPOSE 80
CMD ["nginx", "-g", "daemon off;"]