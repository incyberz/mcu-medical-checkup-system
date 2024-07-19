<style>
  .blok_hasil {
    border: solid 1px #aaa;
    font-size: 10px;
    text-align: left;
    padding: 5px 8px;
    color: black;
    margin: 10px 0;
  }

  .column {
    font-weight: bold;
  }

  .hasil {
    font-family: 'Courier New', Courier, monospace;
  }

  .kolom_header {
    font-weight: 600;
  }

  .detail_header {
    font-weight: 600;
    margin-bottom: 5px;
    letter-spacing: 1px
  }

  @media print {
    body {
      visibility: hidden;
    }

    #kertas {
      visibility: visible;
      position: absolute;
      left: 0;
      top: 0;
      margin: 0;
    }
  }
</style>