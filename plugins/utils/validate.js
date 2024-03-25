export default ({ app }, inject) => {
    inject('vali', {
      isTypeLang(string) {
        try {
          JSON.parse(string)
          {
            const obj = JSON.parse(string)
            return obj.vn
          }
        } catch (err) {
        }
      },

      // validation theo dạng ngày tháng năm
      formatDate(datetime) {
        return moment(datetime, "HH:mm:ss").format("HH:mm");
      },

      // validation theo dạng tiền tệ
      formatCurrency(amount) {
        const formattedAmount = new Intl.NumberFormat('vi-VN', {
            style: 'currency',
            currency: 'VND',
        }).format(amount);

        return formattedAmount;
      },
      
      isType(string, language) {
        try {
          const obj = JSON.parse(string)
          return obj[language]
        } catch (err) {
          console.log(err)
        }
      },

      // lấy giá trị ảnh từ api
      jsonParse(value) {
        try {
          if (value) {
            return JSON.parse(value)
          }
          return ''
        } catch (err) {
        }
      },

      // hàm này dùng để validEmail
      isEmail(email) {
        // eslint-disable-next-line no-useless-escape
        return /^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,4}$/.test(email)
      },

      // hàm này dùng để validate number
      isNumber(value, positive = false) {
        if (positive) {
          return /^\d+$/.test(value) && value > 0
        }
        return /^\d+$/.test(value)
      },

      /**
       * @description: Hàm này dùng để Format Date (ngày tháng năm)
       */
      formatDate(dob) {
        if (dob) {
          dob = new Date(dob)
          // Lấy ra ngày:
          let date = dob.getDate()
          date = date < 10 ? `0${date}` : date
          // Lấy ra tháng:
          let month = dob.getMonth() + 1
          month = month < 10 ? `0${month}` : month
          // Lấy ra năm:
          const year = dob.getFullYear()
          dob = `${date}/${month}/${year}`
        } else {
          dob = ''
        }
        return dob
      },
    })
  }