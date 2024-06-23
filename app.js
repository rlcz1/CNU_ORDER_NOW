class App {
  constructor() {
    this.addEvent();
  }

  addEvent() {
    $(document).on('click', '.add_cart_btn', this.addCart);
    $(document).on('click', '#payment', this.payment);
  }

  // 결제하기 버튼 클릭 이벤트
  payment = (e) => {
    $.ajax({
      url: '../post/payment.php',
      type: 'POST',
    }).done((data) => {
      console.log(data);
      if (data.trim() === 'success') {
        alert('결제가 완료되었습니다.');
        location.href = '../index.php';
      } else {
        alert('결제 실패');
      }
    });
  }

  // 메뉴 담기 버튼 클릭 이벤트
  addCart = (e) => {
    const $this = $(e.currentTarget);
    const foodname = $this.parent().parent().find('.foodName').text();
    const price = $this.parent().parent().find('.price').text();
    const quantity = $this.parent().parent().find('input').val();

    // 장바구니 추가 php로 보내기
    $.ajax({
      url: 'post/cart.php',
      type: 'POST',
      data: {
        foodname: foodname,
        price: price,
        quantity: quantity
      },
    }).done((data) => {
      console.log(data);
      if (data.trim() === 'success') {
        alert('장바구니에 추가되었습니다.');
      } else {
        alert('장바구니 추가 실패');
      }
    });
  }
}

$(document).ready(() => {
  new App();
});