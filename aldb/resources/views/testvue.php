<html lang="{{ app()->getLocale() }}">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>VUE Testing Page</title>

    </head>
    

    <body><!-- Test Vue -->
        <div id="app">
            
            <h1>{{ title }}</h1>
            <form @submit.prevent="submitForm"> 
            <input v-model="title"><br/>
            
            <span class="error" v-show="!message">
                Please enter your message (text is empty)
            </span><br/>
            <textarea v-model="message"></textarea><br/>
            <button style="background-color:lightgrey;" type="Submit" v-if="message" @click="count++;">Send Message [{{ count }}]</button>
            </form>
            <br/>
            <pre>
                {{ $data | json }}
            </pre>
        </div>
       <script src="https://unpkg.com/vue"></script>
        <script>
            new Vue({
                el: '#app',
                methods: {
                    submitForm: function() {
                        alert('Form Submitted!');
                        //e.preventDefault();

                    }
                },
                data: {
                    title: 'Pre-Sessional Assessment',
                    message: '',
                    count: 0

                }
            });
        </script>
        
        <!-- End Test --> 
    </body>
</html>
