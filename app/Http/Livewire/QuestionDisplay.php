<?php

namespace App\Http\Livewire;

use App\Models\Question;
use App\Models\Result;
use App\Models\Resultofmcq;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Support\Facades\Session;
use App\Models\QuestionAttempt;
use Illuminate\Support\Facades\Auth;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;
use Illuminate\Support\Facades\Redirect;


class QuestionDisplay extends Component
{
    use LivewireAlert;
    public $question;
    public $selectedOption;
    public $slno;
    protected $listeners = [
        'submittimer'
    ];
    public $totalQuestions;
    public $question_attem;
    public $is_review;
    public function submittimer(){
        $this->submit();
    }
    public function mount()
    {

        // Retrieve the stored 'slno' from the session
        $this->slno = Session::get('slno', 1);
        if ($this->slno === 0 ) {
            // Set $this->slno to 1 in session
            Session::put('slno', 1);
        }
        // Retrieve the question based on the 'slno'
        $this->question = Question::find($this->slno);
        $this->question_attem=QuestionAttempt::where('user_id', Auth::user()->id)
        ->where('question_id', $this->question->id)->exists()
        ;
       if($this->question_attem==true){
        $this->selectedOption=QuestionAttempt::where('user_id', Auth::user()->id)
        ->where('question_id', $this->question->id)->get()[0]->option;
        $this->is_review=QuestionAttempt::where('user_id', Auth::user()->id)
        ->where('question_id', $this->question->id)->get()[0]->is_review;
       }
       else{
        $this->selectedOption=null;
       }
       
        $this->totalQuestions=Question::all()->count();
 

    }
    public function saveAndNext()
{
    $this->question_attem=QuestionAttempt::where('user_id', Auth::user()->id)
    ->where('question_id', $this->question->id);
    if ($this->question_attem->exists()) {
        $question_response = $this->question_attem->first();
    $question_response->option=$this->selectedOption;
        // Update the existing question response
        $question_response->is_correct = ($this->selectedOption == $this->question->answer);
        $question_response->save();
    
        // Increment the question number
        if ($this->slno < $this->totalQuestions) {
            $this->slno = Session::put('slno', $this->slno + 1);
            $this->mount();
        }
        $this->mount();
    }
   else {
    $question_response = new QuestionAttempt();
    $question_response->user_id = Auth::user()->id;
    $question_response->question_id = $this->question->id;
    $question_response->option=$this->selectedOption;
    $question_response->is_correct = ($this->selectedOption == $this->question->answer);
    $question_response->save();
    if ($this->slno < $this->totalQuestions) {
        $this->slno = Session::put('slno', $this->slno + 1);
        $this->mount();
    }
    $this->mount();
   }


// Retrieve the next question based on the updated 'slno'
$this->question = Question::find($this->slno);

}

public function saveAndMarkForReview()
{
    $this->question_attem=QuestionAttempt::where('user_id', Auth::user()->id)
    ->where('question_id', $this->question->id);
    if ($this->question_attem->exists()) {
        $question_response = $this->question_attem->first();
    
        // Update the existing question response
        $question_response->is_correct = ($this->selectedOption == $this->question->answer);
        $question_response->is_review = 1;
        $question_response->no_answer=1;
        $question_response->option=$this->selectedOption;
        $question_response->save();
    
        // Increment the question number
        if ($this->slno < $this->totalQuestions) {
            $this->slno = Session::put('slno', $this->slno + 1);
            $this->mount();
        }
        $this->mount();
    }
    else{
    $question_response = new QuestionAttempt();
$question_response->user_id = Auth::user()->id;
$question_response->question_id = $this->question->id;
$question_response->is_correct = ($this->selectedOption == $this->question->answer);
$question_response->option=$this->selectedOption;
$question_response->is_review = 1;
$question_response->no_answer=1;
$question_response->save();
$this->slno = Session::put('slno', $this->slno + 1);
$this->mount();
    }
}

public function clearResponse()
{
   $this->selectedOption=null;
}

public function markForReviewAndNext()
{
    $this->question_attem=QuestionAttempt::where('user_id', Auth::user()->id)
    ->where('question_id', $this->question->id);
    if ($this->question_attem->exists()) {
        $question_response = $this->question_attem->first();
    
        // Update the existing question response
       
        $question_response->is_review = 1;
        $question_response->save();
    
        // Increment the question number
        if ($this->slno < $this->totalQuestions) {
            $this->slno = Session::put('slno', $this->slno + 1);
            $this->mount();
        }
        $this->mount();
    }
    else{
    $question_response = new QuestionAttempt();
$question_response->user_id = Auth::user()->id;
$question_response->question_id = $this->question->id;
$question_response->is_review = 1;
$question_response->save();
$this->slno = Session::put('slno', $this->slno + 1);
$this->mount();
    }

}
public function loadQuestion($questionNumber)
{
    // Update the 'slno' session variable to load the selected question
    Session::put('slno', $questionNumber);
    // Reload the component to display the selected question
    $this->mount();
}

public function goBack()
{
   if ($this->slno > 1) {
    $this->slno = Session::put('slno', $this->slno - 1);
    $this->mount();
}
}

public function goNext()
{
    if ($this->slno < $this->totalQuestions) {
        $this->slno = Session::put('slno', $this->slno + 1);
        $this->mount();
    }
   
}

public function submit()
{
    if(Resultofmcq::where('user_id', Auth::user()->id)->exists()){
        if (Resultofmcq::where('user_id', Auth::user()->id)->exists()) {
            $this->alert('warning', 'Already Submitted redirect to result ', [
                'timer' => 2000,           
            ]);
            $this->showAlert = true;
            return Redirect::route('result');
        }
    }
   else{
    $result = new Resultofmcq();
    $result->user_id = Auth::user()->id;
    
    
    $result->result = QuestionAttempt::where('user_id', Auth::user()->id)
                                      ->where('is_correct', 1)
                                      ->count();
    
    $result->total_question = $this->totalQuestions;
    // dd(QuestionAttempt::where('user_id',Auth::user()->id)->where('is_correct',1)->count());
    
   
    $result->save();
    Toastr::success('Add new employee successfully :)', 'Success');
    return Redirect::route('result');
   }
   
   
}


    public function render()
    {
        return view('livewire.question-display');
    }
}
