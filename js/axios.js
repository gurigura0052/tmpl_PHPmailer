var send = {
  methods: {
    // sendが発動した時の動作
    send: function () {
      var _this = this;
      
      // フォームが正しく入力されている場合
      if (_this.isValid) {
        var requestUrl = _this.request.url;
        var requestData = _this.request.data;
        
        // axiosではjsonでデータを送るため、パラメータ形式に変換して送る。
        // URLSearchParamsでrequestDataを変換し、parmsに格納
        var params = new URLSearchParams();
        for (var key in requestData) {
          params.append (key, requestData[key]);
        }
        
        axios
          .post(requestUrl, params)
          // 通信が成功した場合
          .then(function (response) {
            if (response.data.status == 'success') { // 2通とも送信できた場合
              alert('送信しました');
            } else if (response.data.status == 'error'){ // 送信に失敗した場合
              alert('送信に失敗しました。入力を確認してください');
            } else { // statusが返ってこなかった場合
              alert('エラー');
            }
          })
          // 通信に失敗した場合
          .catch(function (error) {
            alert('送信できません。入力を確認してください');
          });
        
      } else { // フォームの入力が正しくない場合
        alert('入力を確認してください');
      }
    }
  }
}

new Vue({
  el: '#form',
  data: {
    addForm: { // フォームに入力された内容
      name: '',
      tel: '',
      mail: '',
      text: ''
    },
    errorMessage: { // 入力が正しくない場合に表示するテキスト
      name: '',
      tel: '',
      mail: ''
    },
    request: { // ajaxで送るデータ
      url: 'php/form/send.php', // 呼び出すurl
      data: { // phpへ送るデータ
        name: '',
        tel: '',
        mail: '',
        text: '',
        userMsg: 'ユーザー向けメールです。',
        adminMsg: '管理者向けメールです。'
      }
    }
  },
  
  computed: {
    // 各フォームのバリデーション
    // 入力内容が正しくなければエラーメッセージを挿入
    nameValid: function() {
      if (this.addForm.name) {
        this.errorMessage.name = ''
      } else {
        this.errorMessage.name = '名前を入力してください'
      }
      
      return this.errorMessage.name
    },
    telValid: function() {
      if (this.addForm.tel.match(/^\d+$/)) {
        this.errorMessage.tel = ''
      } else {
        this.errorMessage.tel = 'ハイフンなしで入力ください'
      }
      
      return this.errorMessage.tel
    },
    mailValid: function() {
      if (this.addForm.mail.match(/.+@.+\..+/)) {
        this.errorMessage.mail = ''
      } else if (!this.addForm.mail){
        this.errorMessage.mail = 'メールアドレスを入力ください'
      } else {
        this.errorMessage.mail = 'メールアドレスが正しいかご確認ください'
      }
      
      return this.errorMessage.mail
    },
    
    // 各フォームが正しく入力されているか確認
    isValid: function () {
      var valid = false // リセット
      
      // 入力内容をajaxへ送るデータに格納
      if(!this.errorMessage.name && !this.errorMessage.tel && !this.errorMessage.mail){
        valid = true
        this.request.data.name = this.addForm.name
        this.request.data.tel = this.addForm.tel
        this.request.data.mail = this.addForm.mail
        this.request.data.text = this.addForm.text
      }
      
      return valid
    }
  },
  
  mixins: [send]
})