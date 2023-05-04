import { functions } from "./functions";

export async function checkStock(productName: string, productAmount: number, res: any) {
    return new Promise(async (resolve, reject) => {
        let sql = `SELECT * FROM \`require\` WHERE craftingProduct = '${productName}'`
        let result: any = await functions.arasql.query(sql)
        if (result.length == 0 || result.length == undefined) {
            res.end("Not enough stock for update.")
            return resolve(false)
        }
        result = result[0]
    
        let needProducts = result.needProducts
        let moreThanOne = false
        if (needProducts.includes(",")) {
            needProducts = needProducts.split(",")
            moreThanOne = true
        }
        const needAmountPerOne = Number(result.needAmountPerOne)
        
        if (moreThanOne == true) {
            let failed: any;
            for await(const needProduct of needProducts) {
                if (failed == true) return resolve(false) 
                sql = `SELECT * FROM stock WHERE \`name\` = '${needProduct}'`
                result = await functions.arasql.query(sql)
                if (result.length == 0) {
                    failed = true
                    continue
                }
                result = result[0]  
                
                const neededAmount = needAmountPerOne * productAmount
                if (result.amount < neededAmount) {
                    failed = true
                    continue
                }
            }
            return resolve(needProducts)
        } else {
            sql = `SELECT * FROM stock WHERE \`name\` = '${needProducts}'`
            result = await functions.arasql.query(sql)
            if (result.length == 0) {
                res.end("Not enough needed products in stocks.")
                return resolve(false)
            }
            result = result[0]
        
            const neededAmount = needAmountPerOne * productAmount
            if (result.amount < neededAmount) {
                res.end("Not enough needed products in stocks.")
                return resolve(false)
            } else return resolve(needProducts)
        }
    })
}