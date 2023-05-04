import express from "express";
import { handlers } from "./Handlers/handlers";
import { functions } from "./Functions/functions";
import config from "./config.json";
export const app = express();

app.listen(config.API.port);
functions.arasql.connect();

// Starting handlers.
handlers.checkProduct();
handlers.insertProduct();
handlers.checkStocks();
handlers.showUsers();
handlers.registerUser();
handlers.loginUser();
handlers.deleteProduct();
handlers.updateProduct();
handlers.checkRequire();
handlers.addRequire();
handlers.updateStock();
handlers.deleteUser();
handlers.updateRequire();

console.log(`API Listening on ${config.API.port} port.`)