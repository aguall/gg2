/**
 * Created by justOP on 20.01.18.
 */
$( document ).ready(
    addNewWord()
);
function addNewWord(){
    $('.add_word').on('click',function(){
        ('.stop_word_col').add('<td>'+"Test"+'</td>')
    })
}