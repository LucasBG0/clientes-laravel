<div class="container my-5">
    <div class="row">
        <div class="col">
            <div class="card">
                <div class="card-header p-0">
                	<h2 class="m-4">Documentação</h2>
                </div>   
                <div class="card-body">
                  <div class="p-4" id="markdown">
                    {{ Illuminate\Mail\Markdown::parse($slot) }}
                  </div>
                </div>
            </div>
        </div>
    </div>
</div>