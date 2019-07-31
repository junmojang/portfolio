function showPasswordPromt(type, request, ann){
  if(ann == 1){
    if(type == "comment"){
        var input = prompt("댓글 비밀번호를 입력해주세요");
        if(input === null) return false; //취소버튼 누르면 null 반환, submit 일어나지 않음

        document.querySelector('#comment_delete_password_check').value = input;
        return true;
    }
    if(type == "post"){
      //  if(!confirm("정말로 삭제하시겠습니까? 삭제된 글은 복구되지 않습니다.")) return false;

      var input = prompt("게시글 비밀번호를 입력해주세요");
      console.log(input);
      if(input === null) return false; //취소버튼 누르면 null 반환, submit 일어나지 않음

      var selected = (request == "delete") ? "#delete_password_check" : "#edit_password_check";
      document.querySelector(selected).value = input;

      return true;
    }
  }
  else{
    return deleteConfirm(type,request);
  }
}

function deleteConfirm(type,request){
  if(type == 'comment'){
     if(!confirm("정말로 삭제하시겠습니까? 삭제된 댓글은 복구되지 않습니다.")) return false;
  }
  else if(request == 'edit'){
    if(!confirm("정말로 수정하시겠습니까? 수정되기 전의 글은 복구되지 않습니다.")) return false;
  }
  else{
    if(!confirm("정말로 삭제하시겠습니까? 삭제된 글은 복구되지 않습니다.")) return false;
  }
}
