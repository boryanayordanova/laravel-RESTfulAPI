<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Validation\ValidationException;

class TransformInput
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */

     //recieve the transformer as well (add $transformer attirbute)
    public function handle($request, Closure $next, $transformer)
    {
        //we have to transform the input with the new array, so create new one
        $transformedInput = [];

        //get the body input fields of body of the POST request
        foreach($request->request->all() as $input => $value){
            //the new original input become the provided input
            //originalAttributes is a function on all the app/Transformers subfiles.
           $transformedInput[$transformer::originalAttributes($input)] = $value;           
            
        }
        
        //replace the original input with the new one
        $request->replace($transformedInput);


        // return $next($request);
        // we need to abotain the response before return it
        $response = $next($request);

        // we need to be sure this response is an error response
        // also if this exception is a validationException
        // import the VelidationException (illuminate)
        if(isset($response->exception) && $response->exception instanceof ValidationException){
            // obtain the data of the response:
            $data = $response->getData();

            // the response has an error, an error code, content of the error. We need the content.
            // it is an array with every attribute that we need to modify
            $transformErrors = [];

            // loop every error
            // in case of $field 'name' we have to transform it to 'title' in Category for examle
            foreach($data->error as $field => $errorMessage){
                $transformedField = $transformer::transformedAttributes($field);

                // we need to fill the new transformed errors
                // the new field transformed is going to be equel to error message. 
                // But error message has the name of the field again. We need to replace every apearance of the value there
                // str_replace(original value, what we want to replace to, where to relpace)
                $transformedErrors[$transformedField] = str_replace($field, $transformedField, $errorMessage );
            }

            // data in the error attribute is going to be equel to the transformed errors
            $data->error = $transformedErrors;

            // we need to specify that new data to the response
            $response->setData($data);
        }

        return $response;
    }


    
}
