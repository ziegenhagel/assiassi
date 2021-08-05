export default function api_call(path,method,body) {
    return fetch(window.APP_URL+path,{
            method,
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify(body)
        }).then(d=>d.json())
}

