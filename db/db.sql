CREATE DATABASE IF NOT EXISTS mydb;
CREATE USER IF NOT EXISTS hutnik IDENTIFIED BY 'Zaq12wsx';
GRANT ALL PRIVILEGES ON mydb.* TO hutnik;

USE mydb;