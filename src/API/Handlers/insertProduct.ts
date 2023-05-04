import { app } from "../main";
import { functions } from "../Functions/functions";

export async function insertProduct() {
    console.log("Handlers > insertProduct Ready.")
    app.get("/insertProduct.aras", async (req, res) => {
        const query = req.query
        const productName = query.productName
        const productAmount = Number(query.productAmount)
        const productStatus = query.productStatus

        const queries = [productName, productAmount, productStatus];

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

        const needProducts: any = await functions.checkStock(String(productName), productAmount, res);
        if (needProducts == false) return

        let sql = `SELECT * FROM products WHERE \`name\` = '${productName}'`
        let result: any = await functions.arasql.query(sql)
        if (result.length > 0) {
            res.end("Already have this product.")
            return
        }

        sql = `INSERT INTO products (\`name\`, amount, status) VALUES ('${productName}', ${productAmount}, '${productStatus}')`
        result = await functions.arasql.query(sql)
        if (result == false) {
            res.end("Query error.")
            return
        } else {
            if (!Array.isArray(needProducts)) {
                sql = `SELECT * FROM \`require\` WHERE craftingProduct = '${productName}'`
                result = await functions.arasql.query(sql)
                result = result[0]

                const needAmountPerOne = result.needAmountPerOne

                sql = `SELECT * FROM stock WHERE \`name\` = '${needProducts}'`
                result = await functions.arasql.query(sql)
                if (result.length == 0 || result.length == undefined) {
                    res.end("Stock product not found.")
                    return
                }
    
                const decreaseAmount = Number(productAmount) * needAmountPerOne
                console.log(decreaseAmount)
                // Elimizde 9 soğan stoğu var. Menemen yapmak için 2 soğan'a ihtiyacımız var. 2 Menemen yaparsak elimizde 5 soğan kalır.
    
                result = result[0]
                if (Number(result.amount) - decreaseAmount < 0) {
                    res.end("Can't decrease this amount.")
                    return
                }
        
                sql = `UPDATE stock SET amount = amount - ${decreaseAmount} WHERE id = ${result.id}`
                result = await functions.arasql.query(sql)
                if (result == false) {
                    res.end("Query error.")
                    return
                }
            } else {
                needProducts.forEach(async (needProduct: any) => {
                    sql = `SELECT * FROM \`require\` WHERE craftingProduct = '${productName}'`
                    result = await functions.arasql.query(sql)
                    result = result[0]
    
                    const needAmountPerOne = result.needAmountPerOne

                    sql = `SELECT * FROM stock WHERE \`name\` = '${needProduct}'`
                    result = await functions.arasql.query(sql)
                    if (result.length == 0 || result.length == undefined) {
                        res.end("Stock product not found.")
                        return
                    }
        
                    const decreaseAmount = Number(productAmount) * needAmountPerOne
                    // Elimizde 9 soğan stoğu var. Menemen yapmak için 2 soğan'a ihtiyacımız var. 2 Menemen yaparsak elimizde 5 soğan kalır.
        
                    result = result[0]
                    if (Number(result.amount) - decreaseAmount < 0) {
                        res.end("Can't decrease this amount.")
                        return
                    }
            
                    sql = `UPDATE stock SET amount = amount - ${decreaseAmount} WHERE id = ${result.id}`
                    result = await functions.arasql.query(sql)
                    if (result == false) {
                        res.end("Query error.")
                        return
                    }
                })
            }
        }

        res.end("true")
    })
}