
![alt Vinz Clortho](https://github.com/jasonbyrne/clortho-lite/blob/master/vinz-clortho.png?raw=true)

# Clortho-Lite

Let's give credit where it is due. This package is derived from: https://github.com/zetlen/clortho

The reason that I rewrote it was because it came shipped with some UX elements that I didn't want. It hadn't been updated in a while. And I like dealing with TypeScript better, so I decided to convert it.

## Basic Usage

```javascript
const clortho = require('clortho-lite').clortho;

let service = clortho('Whatever Name of Your Service');

service.set('some-user-name', 'some-password')
    .then(function (credentials) {
        console.log(credentials);
    })
    .catch(function (err) {
        console.log(err);
    });

service.get('some-user-name')
    .then(function (credentials) {
        console.log(credentials);
    })
    .catch(function (err) {
        console.log(err);
    });

```

That's about it.