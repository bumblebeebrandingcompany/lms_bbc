@if(count($documents) > 0)
    @foreach($documents as $index => $document)
        <div class="card card-primary card-outline">
            <div class="card-header" id="document_{{$index}}">
            <h5 class="card-title w-100 mb-0">
                <button class="btn btn-link faq_question" data-toggle="collapse" 
                    data-target="#doc_acc_collapse_{{$index}}" aria-expanded="true" aria-controls="doc_acc_collapse_{{$index}}">
                    {{$document->title}}
                </button>
            </h5>
            </div>
            <div id="doc_acc_collapse_{{$index}}" class="collapse @if($index == 0) show @endif" aria-labelledby="document_{{$index}}" data-parent="#document_accordion">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-12 faq_answer">
                            {!!$document->details!!}
                        </div>
                        <div class="col-md-12 mt-3">
                            <label for="note_{{$index}}">
                                @lang('messages.note')
                            </label>
                            <textarea name="note" id="note_{{$index}}" rows="2" class="form-control"></textarea>
                            <button type="button" class="btn btn-outline-primary send_doc_to_lead mt-2"
                                data-href="{{route('admin.share.lead.doc', ['lead_id' => $lead->id, 'doc_id' => $document->id])}}">
                                @lang('messages.send_to_lead')
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endforeach
@else
    <div class="callout callout-warning">
        <h5>No, documents found.</h5>
    </div>
@endif