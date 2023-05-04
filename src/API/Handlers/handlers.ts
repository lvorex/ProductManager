import { registerUser } from "./Admin/registerUser";
import { showUsers } from "./Admin/showUsers";
import { checkProduct } from "./checkProduct";
import { checkStocks } from "./checkStocks";
import { deleteProduct } from "./deleteProduct";
import { insertProduct } from "./insertProduct";
import { loginUser } from "./loginUser";
import { updateProduct } from "./updateProduct";
import { checkRequire } from "./checkRequire";
import { addRequire } from "./Admin/addRequire";
import { updateStock } from "./updateStock";
import { deleteUser } from "./Admin/deleteUser";
import { updateRequire } from "./Admin/updateRequire";

export const handlers = {
    checkProduct,
    insertProduct,
    checkStocks,
    showUsers,
    registerUser,
    loginUser,
    deleteProduct,
    updateProduct,
    checkRequire,
    addRequire,
    updateStock,
    deleteUser,
    updateRequire
}