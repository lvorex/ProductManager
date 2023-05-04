import { app } from "../../main";
import { functions } from "../../Functions/functions";

export async function addRequire() {
    console.log("Handlers > addRequire Ready.")
    app.get("/addRequire.aras", async (req, res) => {
        const query = req.query
        const craftingProduct = query.craftingProduct
        const needProducts = query.needProducts
        const needAmountPerOne = query.needAmountPerOne

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

        if (!craftingProduct || !needProducts || !needAmountPerOne) {
            res.end("Wrong usage.")
            return
        }

        let sql = `SELECT * FROM \`require\` WHERE craftingProduct = '${craftingProduct}'`
        let result: any = await functions.arasql.query(sql)
        if (result.length > 0) {
            res.end("Already have require list.")
            return
        }

        sql = `INSERT INTO \`require\` (craftingProduct, needProducts, needAmountPerOne) VALUES ('${craftingProduct}', '${needProducts}', ${needAmountPerOne})`
        result = await functions.arasql.query(sql)
        if (result == false) {
            res.end("Query error.")
            return
        }

        res.end("true")
    })
}