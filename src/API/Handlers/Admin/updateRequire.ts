import { app } from "../../main";
import { functions } from "../../Functions/functions";

export async function updateRequire() {
    console.log("Handlers > updateRequire Ready.")
    app.get("/updateRequire.aras", async (req, res) => {
        const query = req.query
        const craftingProduct = query.craftingProduct
        const needProducts = query.needProducts
        const needAmountPerOne = query.needAmountPerOne

        console.log(needAmountPerOne);
        console.log(needProducts);

        const queries = [craftingProduct, needProducts, needAmountPerOne];

        let injection = false
        for (let i = 0; i < queries.length; i++) {
            if (injection == true) continue
            const selectedQuery = queries[i]
            const injectTest = functions.injectionCheck(String(selectedQuery))
            if (injectTest == true) {
                injection = true
            }
        }
        if (injection == true) {
            res.end("Security problems with used character. Please don't use it.")
            return
        }

        if (!craftingProduct) {
            res.end("Wrong usage.")
            return
        }

        let sql = `SELECT * FROM \`require\` WHERE craftingProduct = '${craftingProduct}'`
        let result: any = await functions.arasql.query(sql)
        if (result.length == 0) {
            res.end("Require not found.")
            return
        }

        sql = `UPDATE \`require\` SET`
        let i = 0
        if (needProducts) {
            sql = sql + ` needProducts = '${needProducts}'`
            i++
        }
        if (needAmountPerOne) {
            if (i != 0) {
                sql = sql + `, needAmountPerOne = '${needAmountPerOne}'`
            } else {
                sql = sql + ` needAmountPerOne = '${needAmountPerOne}'`
            }
            i++
        }

        if (i == 0) {
            res.end("Wrong usage.")
            return
        }

        sql = sql + ` WHERE craftingProduct = '${craftingProduct}'`
        console.log(sql)
        result = await functions.arasql.query(sql)
        if (result == false) {
            res.end("Query error.")
            return
        }

        res.end("true")
    })
}