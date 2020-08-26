import { Flagpole } from "flagpole";

const suite = Flagpole.suite('Basic Smoke Test of Site');

suite.html("Homepage Loads")
   .open("/")
   .next(async (context) => {
     context.assert(context.response.statusCode).equals(200);      
   });

