<div class="color-body">
    <div class="form-area">
    <div class="form-radio form-silver">
        <input type="radio" class="color-radio" id="color-silver">
        <label  class="color-label" for="color-silver">
            <span>
                <img class="color-image" src="../../../asset/images/silver.png">
            </span>
            <span>실버</span>
        </label>
    </div>
    <div class="form-radio form-space">
        <input type="radio" class="color-radio" id="color-space">
        <label  class="color-label" for="color-space">
            <span>
                <img class="color-image" src="../../../asset/images/space-gray.png">
            </span>
            <span>스페이스그레이</span>
        </label>
    </div>
    <div class="form-radio form-green">
        <input type="radio" class="color-radio" id="color-green">
        <label  class="color-label" for="color-green">
            <span>
                <img class="color-image" src="../../../asset/images/midnight-green.png">
            </span>
            <span>미드나이트 그린</span>
        </label>
    </div>
    <div class="form-radio form-gold">
        <input type="radio" class="color-radio" id="color-gold">
        <label  class="color-label" for="color-gold">
            <span>
                <img class="color-image" src="../../../asset/images/gold.png">
            </span>
            <span>골드</span>
        </label>
    </div>
</div>
</div>
<script>
    const radio = document.getElementsByClassName('color-radio');
    const silver = document.getElementById('color-silver');
    console.log(silver);

    if(silver == [document.getElementById('color-silver')]){
        Element.innerHTML='<img src="../../../asset/images/silver.png">'
        }
</script>