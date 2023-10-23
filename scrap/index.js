const fs = require('fs');
const puppeteer = require('puppeteer');

async function run() {
    const browser = await puppeteer.launch();
    const page = await browser.newPage();
    await page.goto('https://www.stripe.com');
  
    // await page.screenshot({ path: 'example.png', fullPage: true });
    // await page.pdf({ path: 'example.pdf', format: 'A4' } );

    // const html = await page.content();

    // const title = await page.evaluate(() => document.title); 

    // const text = await page.evaluate(() => document.body.innerText);

    /*
    const links = await page.evaluate(() =>
        Array.from(document.querySelectorAll('a'), (e) => ({
            title: e.innerText.trim(),
            url: e.href
        }))
    );
    */
    const links = await page.$$eval('a', (elements) =>
        elements.map(e => ({
            title: e.innerText.trim(),
            url: e.href
        }))
    );
    // console.log(links)
    

    // save data to json file
    fs.writeFile('links.json', JSON.stringify(links), (err) => {
        if(err) throw err;
        console.log('File saved')
    });


    await browser.close();
}

run();

// https://pptr.dev
// https://youtu.be/S67gyqnYHmI