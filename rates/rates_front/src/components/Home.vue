<script >
import axios from 'axios';
import Chart from 'chart.js/auto';

export default {
  name:'Home',
  props:{

  },

  data() {
    return {
      currencies: [],
      allData:[],
      dataLength:null,
      selectedCurrency: 'EUR',
      selectedCurrency2: 'USD',
      baseName: "Euro",
      baseRate: 1,
      exchangeName: 'United States Dollar',
      exchangeRate: null,
      calculate: '',
    };
  },
  mounted() {
    axios.get('http://127.0.0.1:8000/fetch_currencyname')
      .then(response => {
        this.currencies = response.data;
        
      })
      .catch(error => {
        console.error(error);
      });

      axios.get('http://127.0.0.1:8000/alldata')
      .then(response => {
        this.allData = response.data;
        this.dataLength = this.allData.length;
        this.updateExchangeRate();
        
      })
      .catch(error => {
        console.error(error);
      });
    
  
      const canvas = this.$refs.myChart;
const ctx = canvas.getContext('2d');

// Fetch data from JSON file
fetch('http://127.0.0.1:8000/allhistory')
  .then(response => response.json())
  .then(data => {
    const uniqueTimestamps = new Set(data.map(d => d.timestamp));
    const labels = Array.from(uniqueTimestamps);
    const usd = [];
    const try_ = [];
    const eur = [];
    data.forEach(d => {
      if (d.currency === "USD") {
        usd.push(d.rate);
      } else if (d.currency === "TRY") {
        try_.push(d.rate);
      } else if (d.currency === "EUR") {
        eur.push(d.rate);
      }
    });
 
    // Update chart data with fetched data
    this.chart = new Chart(ctx, {
      type: 'line',
      data: {
        labels: labels,
        datasets: [
          {
            label: 'USD',
            data: usd,
            borderColor: 'red',
            fill: false,
          },
          {
            label: 'TRY',
            data: try_,
            borderColor: 'blue',
            fill: false,
          },
          {
            label: 'EUR',
            data: eur,
            borderColor: 'green',
            fill: false,
          },
        ],
      },
      options: {
        responsive: true,
        maintainAspectRatio: false,
      },
    });
  })
  .catch(error => {
    console.error(error);
  });
  },

  methods: {
  
    cickReset(){
      this.baseRate = 1;
    },
    // do something with the selected currency
    handleCurrencySelection(){
      this.updateExchangeRate();
    },
    updateExchangeRate() {
     // console.log(this.selectedCurrency);
      //console.log(this.selectedCurrency2);
      if(this.selectedCurrency === 'EUR'){
          this.exchangeName = 'Euro';
          for(let i=0;i<this.dataLength;i++){
           if(this.allData[i].currency === 'EUR'){
            this.exchangeRate = this.allData[i].rate;
            
            break;
           }
          }
        
        
        }
      else if(this.selectedCurrency === 'TRY'){
        this.exchangeName = 'Turkish Lira';
          for(let i=0;i<this.dataLength;i++){
           if(this.allData[i].currency === 'TRY'){
            this.exchangeRate = this.allData[i].rate;
            
            break;
           }
          }
      }
      else{
        this.exchangeName = 'United States Dollar';
          for(let i=0;i<this.dataLength;i++){
           if(this.allData[i].currency === 'USD'){
            this.exchangeRate = this.allData[i].rate;
            
            break;
           }
          }
      }
     
    },

  },
  watch: {
    selectedCurrency() {
      this.updateExchangeRate();
    },
    selectedCurrency2() {
      this.updateExchangeRate();
    },
  },
  computed: {
  exchangeRateCalc() {
      return (this.baseRate/this.exchangeRate).toFixed(3);
    
  },
},

};
</script>

<template>
 
  <div class="container">
 
    <div class="left_section-containter">

      <div class="text_rates">
        <p style="color:white;" >{{baseRate}} {{exchangeName}} <span style="color:#6AFFD2; font-weight: 700;">equal</span></p>
        <h2 style="color:white;">{{exchangeRateCalc }} {{ baseName }}</h2>
      </div>

      <div class="all_labels">
        <div class="label_one">
          <input type="number" id="number" name="number" v-model="baseRate"  min="1" max="Infinity" step="1">

          <select v-model="selectedCurrency" @change="handleCurrencySelection">
            <option v-for="currency in currencies" :value="currency" >{{ currency }}</option>
          </select>
        </div>

        <div class="label_two">
          <input type="number" id="number_2" name="number_2" v-model="exchangeRateCalc "   readonly >

          <select v-model="selectedCurrency2"  disabled>
            <option v-for="currency in currencies" :value="currency" >EUR</option>
          </select>

        </div>
       
        <button @click="cickReset">Reset</button>
      </div>
      
    </div>

    <div>
    <canvas ref="myChart"></canvas>
    </div>
  </div>
   
</template>

<style>
*{
  font-family: 'Segoe UI';
}
    .container{
      display: flex;
      justify-content: space-between;
      margin: 0 4rem 0 4rem;
    }
    .container canvas{
      width: 600px;
    }
    .left_section-containter{
      display: flex;
      flex-direction: column;
      justify-items: center;
      
      
    }
    .label_one{
      display: flex;
      flex-direction: row;
    }
    .label_two{
      display: flex;
      flex-direction: row;
    }
    .all_labels input{
      background: #E3E3E3;
      border-radius: 5px;
      width: 200px;
      height: 30px;
      border: none;
      margin: 0.5rem 0 0.5rem 0;
      
    }
    
    .all_labels select{
      background: #E3E3E3;
      width: 60px;
      height: 31px;
      border: none;
      margin: 0.5rem 0 0.5rem 0.5rem;
    
    }
    .left_section-containter button{
      width: 300px;
      height: 31px;
      border: none;
      background: #6AFFD2;
      box-shadow: 0px 4px 4px rgba(0, 0, 0, 0.25);
      cursor: pointer;
      margin-top: 0.5rem;
    }    
    .left_section-containter button:hover{
      background-color: grey;
      color:white;
    }
</style>
